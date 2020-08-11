<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Benchmark
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="boolean") */
    private $ready = true;

    /** @ODM\Field(type="integer") */
    private $priority = 1;

    /** @ODM\Field(type="collection") */
    private $tags = ['a', 'b', 'c'];

    /**
     * Benchmark constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}