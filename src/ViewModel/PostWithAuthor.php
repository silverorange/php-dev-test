<?php

declare(strict_types=1);

namespace silverorange\DevTest\ViewModel;

final class PostWithAuthor
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $body,
        public readonly string $created_at,
        public readonly string $modified_at,
        public readonly string $author,
        public readonly string $author_name
    ) {}
}
