<?php

namespace silverorange\DevTest\Model;

class Author
{
    public function __construct(
        public string $id,
        public string $full_name,
        public string $created_at,
        public string $modified_at,
    ) {}

    /**
     * Factory method when instance serves as Data Transfer Object.
     *
     * @param array<string, string> $data
     * */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            full_name: (string) $data['full_name'],
            created_at: (string) $data['created_at'],
            modified_at: (string) $data['modified_at']
        );
    }
}
