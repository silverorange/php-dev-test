<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use silverorange\DevTest\Model\Author;

interface AuthorRepositoryInterface
{
    public function findById(string $id): ?Author;
}
