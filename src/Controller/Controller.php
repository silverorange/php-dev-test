<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use PDO;
use silverorange\DevTest\Repository\PostRepositoryInterface;

abstract class Controller
{
    /**
     * @param array<int, string> $params
     * @param ?PDO $db
     * @param ?PostRepositoryInterface $postRepository
     */
    public function __construct(
        protected array $params,
        protected ?PDO $db,
        protected ?PostRepositoryInterface $postRepository,
    ) {
        $this->loadData();
    }

    public function getStatus(): string
    {
        return $this->getProtocol() . ' 200 OK';
    }

    public function getContentType(): string
    {
        return 'text/html';
    }

    abstract public function getContext(): Context;
    abstract public function getTemplate(): Template\Template;

    public function sendHeaders(): void
    {
        header($this->getStatus());
        header('Content-Type: ' . $this->getContentType());
    }

    protected function getProtocol(): string
    {
        return is_string($_SERVER['SERVER_PROTOCOL'])
            ? $_SERVER['SERVER_PROTOCOL']
            : 'HTTP/1.0';
    }

    protected function loadData(): void {}
}
