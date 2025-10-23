<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use PDO;
use silverorange\DevTest\Model\Post;

final class PdoPostRepository implements PostRepositoryInterface
{
    public function __construct(private readonly PDO $db) {}

    /**
     * Retrieve object from DB record, by ID.
     *
     * @param string $id UUID of the post to retrieve
     * @return ?Post Post instance if found, null otherwise
     */
    public function findById(string $id): ?Post
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        if (! $stmt->execute()) {
            return null;
        }

        /** @var array<string, string> $row */
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return Post::fromArray($row);
    }
}
