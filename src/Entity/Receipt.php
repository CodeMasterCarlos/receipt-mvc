<?php

namespace Codemastercarlos\Receipt\Entity;

use DateTimeImmutable;

class Receipt
{
    public readonly string|null $id;

    public readonly string $idUser;

    public readonly string $title;

    public readonly string $image;

    public readonly DateTimeImmutable $date;

    public function __construct(string $idUser, string $title, string $image, DateTimeImmutable $date)
    {
        $this->idUser = $idUser;
        $this->title = $title;
        $this->image = $image;
        $this->date = $date;
    }


    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function formartDateEUA(): string
    {
        return $this->date->format("Y-m-d");
    }

    public function formartDateBR(): string
    {
        return $this->date->format("d/m/Y");
    }

    public function getPath(): string
    {
        return $this->date->format("/Y/m/");
    }
}