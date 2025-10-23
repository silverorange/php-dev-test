<?php

declare(strict_types=1);

namespace silverorange\DevTest\Repository;

use silverorange\DevTest\Model\Post;

interface PostRepositoryInterface
{
    public function findById(string $id): ?Post;
}
