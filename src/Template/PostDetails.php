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
        $context->content = $this->converter->convert($context->content)->getContent();

        return <<<HTML
            <h1>{$context->title}</h1>
            <p>{$context->content}</p>
            <p><em>Author: {$context->author}</em></p>
            HTML;
    }
}
