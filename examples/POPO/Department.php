<?php

namespace ABGEO\POPO\Example;

class Department
{
    /**
     * @var string
     */
    private $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
