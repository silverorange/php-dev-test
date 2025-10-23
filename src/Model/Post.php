<?php

namespace silverorange\DevTest\Model;

class Post
{
    /** @var array<int, string> */
    public const REQUIRED_FIELDS = ['id', 'title', 'body', 'created_at', 'modified_at', 'author'];
    /** @var array<int, string> */
    public const DATE_FIELDS = ['created_at', 'modified_at'];

    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public string $created_at,
        public string $modified_at,
        public string $author,
    ) {}

    /**
     * @param array<string, string> $data
     * */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            title: (string) $data['title'],
            body: (string) $data['body'],
            created_at: (string) $data['created_at'],
            modified_at: (string) $data['modified_at'],
            author: (string) $data['author']
        );
    }

    public function validate(): bool
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (! isset($this->$field) || trim((string) $this->$field) === '') {
                return false;
            }
        }

        foreach (self::DATE_FIELDS as $dateField) {
            if (! strtotime($this->$dateField)) {
                return false;
            }
        }

        return true;
    }
}
