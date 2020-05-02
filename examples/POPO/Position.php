<?php

namespace ABGEO\POPO\Example;

class Position
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var \ABGEO\POPO\Example\Department
     */
    private $department;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): void
    {
        $this->department = $department;
    }
}
