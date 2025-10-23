<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use silverorange\DevTest\ViewModel\PostWithAuthor;

interface PostRepositoryInterface
{
    /**
     * Retrieve mapped post with related author name from the database record, by ID.
     *
     * @param string $id UUID of the post to retrieve
     * @return ?PostWithAuthor PostWithAuthor instance if found, null otherwise
     */
    public function findByIdWithAuthor(string $id): ?PostWithAuthor;

    /**
     * Retrieve all mapped posts with related author name from the database, ordered by creation date descending.
     *
     * @return array<int, PostWithAuthor> Array of PostWithAuthor instances
     */
    public function findAllWithAuthor(): array;
}
