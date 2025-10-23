<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use PDO;
use silverorange\DevTest\Model\Author;

final class PdoAuthorRepository implements AuthorRepositoryInterface
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @inheritDoc
     */
    public function findById(string $id): ?Author
    {
        $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return null;
        }

        /** @var array<string, string> $row */
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return Author::fromArray($row);
    }
}
