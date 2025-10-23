<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use silverorange\DevTest\Model\Author;

interface AuthorRepositoryInterface
{
    /**
     * Retrieve mapped object from database record, by ID.
     *
     * @param string $id UUID of the author to retrieve
     * @return ?Author Author instance if found, null otherwise
     */
    public function findById(string $id): ?Author;
}
