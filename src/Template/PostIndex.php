<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;
use silverorange\DevTest\ViewModel\PostWithAuthor;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        $postListItems = '';
        if ($context->postsWithAuthor) {
            /** @var PostWithAuthor $postWithAuthor */
            foreach ($context->postsWithAuthor as $postWithAuthor) {
                $postListItems .= '<li><a href="/posts/' . $postWithAuthor->id . '">' . $postWithAuthor->title . '</a> by ' . $postWithAuthor->author_name . '</li>';
            }
        }

        return <<<HTML
            <p>SHOW ALL {$context->content} POSTS HERE</p>
            <ul>{$postListItems}</ul>
            HTML;
    }
}
