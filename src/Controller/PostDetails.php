<?php

namespace silverorange\DevTest\Controller;

use League\CommonMark\ConverterInterface;
use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\ViewModel\PostWithAuthor;
use silverorange\DevTest\Repository\AuthorRepositoryInterface;
use silverorange\DevTest\Repository\PostRepositoryInterface;
use silverorange\DevTest\Schema\UuidValidatorTrait;

class PostDetails extends Controller
{
    use UuidValidatorTrait;

    /** @var array<int, ?PostWithAuthor> */
    private array $postsWithAuthor = [];

    public function __construct(
        protected array $params,
        protected ?AuthorRepositoryInterface $authorRepository,
        protected ?PostRepositoryInterface $postRepository,
        private readonly ConverterInterface $converter
    ) {
        parent::__construct($params, null, $authorRepository, $postRepository);
    }

    public function getContext(): Context
    {
        $context = new Context();

        if (! isset($this->postsWithAuthor[0])) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
        } else {
            $context->title = $this->postsWithAuthor[0]->title;
            $context->postsWithAuthor = $this->postsWithAuthor;
        }

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        if (! isset($this->postsWithAuthor[0])) {
            return new Template\NotFound();
        }

        return new Template\PostDetails($this->converter);
    }

    public function getStatus(): string
    {
        if (! isset($this->postsWithAuthor[0])) {
            return $this->getProtocol() . ' 404 Not Found';
        }

        return $this->getProtocol() . ' 200 OK';
    }

    protected function loadData(): void
    {
        if (! $this->params || ! $this->validateParams()) {
            return;
        }

        if ($this->postRepository === null || $this->authorRepository === null) {
            return;
        }

        $this->postsWithAuthor[] = $this->postRepository->findByIdWithAuthor($this->params[0]) ?: null;
    }

    private function validateParams(): bool
    {
        if (! isset($this->params[0])) {
            return false;
        }

        $id = trim($this->params[0]);
        if ($id === '') {
            return false;
        }

        return $this->isValidUuid($id);
    }
}
