<?php

namespace App\Platform\Database\ObjectMapper;

use IteratorIterator;
use mysqli_result;

class MysqliMappingIterator extends IteratorIterator
{
    private string $className;
    private int    $position = 0;

    public function __construct(mysqli_result $result, string $className)
    {
        parent::__construct($result);

        $this->className = $className;
    }

    public function __destruct()
    {
        parent::getInnerIterator()->free();
    }

    public function current()
    {
        return parent::getInnerIterator()->fetch_object($this->className);
    }

    public function rewind()
    {
        $this->position = 0;
        parent::getInnerIterator()->data_seek($this->position);
    }

    public function next()
    {
        $this->position++;
    }

    public function valid()
    {
        return $this->position < parent::getInnerIterator()->num_rows;
    }

    public function key()
    {
        return $this->position;
    }
}