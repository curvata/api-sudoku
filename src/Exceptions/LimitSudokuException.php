<?php

namespace App\Exceptions;

use Exception;

class LimitSudokuException extends Exception
{
    public function __construct()
    {
        $this->message = "Vous ne pouvez générer qu'entre 1 et 10 grilles !";
    }
}