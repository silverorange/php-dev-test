<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use silverorange\DevTest\Model\Post;
use silverorange\DevTest\ViewModel\PostWithAuthor;

interface PostRepositoryInterface
{
    /**
     * Retrieve mapped object from database record, by ID.
     *
     * @param string $id UUID of the post to retrieve
     * @return ?Post Post instance if found, null otherwise
     */
    public function findById(string $id): ?Post;

    /**
     * Retrieve all mapped posts with related author name from the database, ordered by creation date descending.
     *
     * @return array<int, PostWithAuthor> Array of PostWithAuthor instances
     */
    public function findAllWithAuthor(): array;
}
