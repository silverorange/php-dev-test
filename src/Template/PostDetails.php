<?php

namespace silverorange\DevTest\Template;

use League\CommonMark\ConverterInterface;
use silverorange\DevTest\Context;

class PostDetails extends Layout
{
    public function __construct(private readonly ConverterInterface $converter)
    {
        parent::__construct();
    }

    protected function renderPage(Context $context): string
    {
        if (! $context->postsWithAuthor || ! isset($context->postsWithAuthor[0])) {
            return <<<HTML
                <h1>{$context->title}</h1>
                <p>{$context->content}</p>
                HTML;
        }

        $body = $this->converter->convert($context->postsWithAuthor[0]->body)->getContent();

        return <<<HTML
            <h1>{$context->title}</h1>
            <p>{$body}</p>
            <p><em>Author:</em> {$context->postsWithAuthor[0]->author_name}</p>
            HTML;

    }
}
