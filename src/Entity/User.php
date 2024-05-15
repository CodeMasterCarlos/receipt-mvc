<?php

namespace Codemastercarlos\Receipt\Entity;

use DateTimeImmutable;

class User
{
    public readonly string|null $id;

    public readonly string $name;

    public readonly string $email;

    public readonly string|null $password;

    public readonly DateTimeImmutable $dateCreated;

    public function __construct(string $name, string $email, ?string $password, DateTimeImmutable $dateCreated)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->dateCreated = $dateCreated;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function formartDateEUA(): string
    {
        return $this->dateCreated->format("Y-m-d H:i:s");
    }
}