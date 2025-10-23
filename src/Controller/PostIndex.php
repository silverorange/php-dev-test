<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Repository\PostRepositoryInterface;
use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\ViewModel\PostWithAuthor;

class PostIndex extends Controller
{
    /** @var array<int, PostWithAuthor> $postsWithAuthor */
    private array $postsWithAuthor = [];

    public function __construct(
        protected array $params,
        protected ?PostRepositoryInterface $postRepository,
    ) {
        parent::__construct($params, null, $postRepository);
    }

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts';
        $context->postsWithAuthor = $this->postsWithAuthor;

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostIndex();
    }

    protected function loadData(): void
    {
        if ($this->postRepository === null) {
            return;
        }

        $this->postsWithAuthor = $this->postRepository->findAllWithAuthor();
    }
}
