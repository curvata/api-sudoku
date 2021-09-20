<?php

namespace App\Exceptions;

use Exception;

class BadModeSudokuException extends Exception
{
    public function __construct()
    {
        $this->message = "Ce mode n'est pas valide !";
    }
}