<?php

namespace App\Exceptions;

use Exception;

class LimitSudokuException extends Exception
{
    public function __construct(int $limit)
    {
        $this->message = "La limite est de ".$limit." grilles";
    }
}