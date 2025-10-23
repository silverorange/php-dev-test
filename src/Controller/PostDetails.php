<?php

namespace silverorange\DevTest\Controller;

use League\CommonMark\ConverterInterface;
use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Repository\AuthorRepositoryInterface;
use silverorange\DevTest\Repository\PostRepositoryInterface;
use silverorange\DevTest\Schema\UuidValidatorTrait;

class PostDetails extends Controller
{
    use UuidValidatorTrait;

    private ?Post $post = null;
    private ?string $authorName = null;

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

        if ($this->post === null) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
            $context->author = null;
        } else {
            $context->title = $this->post->title;
            $context->content = $this->post->body;
            $context->author = $this->authorName;
        }

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        if ($this->post === null) {
            return new Template\NotFound();
        }

        return new Template\PostDetails($this->converter);
    }

    public function getStatus(): string
    {
        if ($this->post === null) {
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

        $this->post = $this->postRepository->findById($this->params[0]) ?: null;

        if ($this->post) {
            $author = $this->authorRepository->findById($this->post->author) ?: null;
            $this->authorName = $author->full_name ?? 'Unknown';
        }
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
