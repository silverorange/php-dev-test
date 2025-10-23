<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use PDO;
use silverorange\DevTest\ViewModel\PostWithAuthor;

final class PdoPostRepository implements PostRepositoryInterface
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @inheritDoc
     */
    public function findByIdWithAuthor(string $id): ?PostWithAuthor
    {
        $sql = <<<'SQL'
                SELECT p.*, a.full_name AS author_name
                FROM posts p
                LEFT JOIN authors a ON a.id = p.author
                WHERE p.id = :id
            SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return null;
        }

        /** @var array<string, string> $row */
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $postWithAuthor = new PostWithAuthor(
            id: (string) $row['id'],
            title: (string) $row['title'],
            body: (string) $row['body'],
            created_at: (string) $row['created_at'],
            modified_at: (string) $row['modified_at'],
            author: (string) $row['author'],
            author_name: (string) $row['author_name']
        );

        return $postWithAuthor;
    }

    /**
     * @inheritDoc
     */
    public function findAllWithAuthor(): array
    {
        $sql = <<<'SQL'
                SELECT p.*, a.full_name AS author_name
                FROM posts p
                LEFT JOIN authors a ON a.id = p.author
                ORDER BY p.created_at DESC
            SQL;
        $stmt = $this->db->prepare($sql);

        if (! $stmt->execute()) {
            return [];
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $postsWithAuthor = [];
        /** @var array<string, string> $row */
        foreach ($rows as $row) {
            $postsWithAuthor[] = new PostWithAuthor(
                id: (string) $row['id'],
                title: (string) $row['title'],
                body: (string) $row['body'],
                created_at: (string) $row['created_at'],
                modified_at: (string) $row['modified_at'],
                author: (string) $row['author'],
                author_name: (string) $row['author_name']
            );
        }

        return $postsWithAuthor;
    }
}
