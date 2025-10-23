<?php

declare(strict_types=1);

namespace silverorange\DevTest\Scripts;

use Exception;
use FilesystemIterator;
use GlobIterator;
use PDO;
use RuntimeException;
use SplFileInfo;
use silverorange\DevTest\Config;
use silverorange\DevTest\Database;
use silverorange\DevTest\Model\Post;

final class PostsImporter
{
    private const DATA_DIR = __DIR__ . '/../../data';

    public function __construct(private readonly PDO $db) {}

    public function import(): void
    {
        try {
            echo "Importing data from files in directory: " . self::DATA_DIR . PHP_EOL;

            $this->validateDirectory();

            $this->db->beginTransaction();

            $hasFiles = $this->processFiles();
            if (! $hasFiles) {
                throw new RuntimeException("No JSON files found in data directory: " . self::DATA_DIR);
            }

            $this->db->commit();
            echo "Import completed successfully" . PHP_EOL;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new RuntimeException("Import failed, if any, changes rolled back: " . $e->getMessage());
        }
    }

    private function processFiles(): bool
    {
        $iterator = new GlobIterator(self::DATA_DIR . "/*.json", FilesystemIterator::CURRENT_AS_FILEINFO);
        if (! $iterator->count()) {
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO posts (id, title, body, created_at, modified_at, author)
            VALUES (:id, :title, :body, :created_at, :modified_at, :author)
        ");

        /** @var SplFileInfo $fileinfo */
        foreach ($iterator as $fileinfo) {
            try {
                $this->validateFile($fileinfo);

                $jsonData = file_get_contents($fileinfo->getPathname());
                if ($jsonData === false) {
                    throw new RuntimeException("Failed to read file contents.");
                }

                /** @var array<string, string> $jsonDataArray */
                $jsonDataArray = json_decode($jsonData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new RuntimeException("Invalid JSON: " . json_last_error_msg());
                }

                $post = Post::fromArray($jsonDataArray);
                if (! $post->validate()) {
                    throw new RuntimeException("Post data validation failed.");
                }

                foreach (Post::REQUIRED_FIELDS as $field) {
                    $stmt->bindValue(":{$field}", $post->$field, PDO::PARAM_STR);
                }
                try {
                    $stmt->execute();
                    echo "✓ Imported: {$fileinfo->getFilename()}" . PHP_EOL;
                } catch (Exception $e) {
                    echo "✗ DB error for {$fileinfo->getFilename()}: " . $e->getMessage() . PHP_EOL;
                }
            } catch (Exception $e) {
                echo "✗ Error processing file {$fileinfo->getFilename()}: " . $e->getMessage() . PHP_EOL;
            }
        }

        return true;
    }

    private function validateDirectory(): void
    {
        if (! is_dir(self::DATA_DIR)) {
            throw new RuntimeException("Data directory does not exist: " . self::DATA_DIR);
        }
        if (! is_readable(self::DATA_DIR)) {
            throw new RuntimeException("Data directory is not readable: " . self::DATA_DIR);
        }
    }

    private function validateFile(SplFileInfo $fileinfo): void
    {
        if (! $fileinfo->isFile()) {
            throw new RuntimeException("Not a regular file: " . $fileinfo->getFilename());
        }
        if (! $fileinfo->isReadable()) {
            throw new RuntimeException("File is not readable: " . $fileinfo->getFilename());
        }
    }
}

require __DIR__ . '/../../vendor/autoload.php';

$config = new Config();
$db = (new Database($config->dsn))->getConnection();

$importer = new PostsImporter($db);
try {
    $importer->import();
} catch (Exception $e) {
    echo "Fatal Error: {$e->getMessage()}" . PHP_EOL;
    exit(1);
}
