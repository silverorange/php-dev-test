<?php

namespace silverorange\DevTest;

use silverorange\DevTest\ViewModel\PostWithAuthor;

class Context
{
    // TODO: You can add more properties to this class to pass values to templates

    public string $title = '';

    public string $content = '';

    public ?string $author = null;

    /** @var array<int, PostWithAuthor> */
    public ?array $postsWithAuthor = null;
}
