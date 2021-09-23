<?php

namespace App\Interface;

Interface ValidateInterface
{
    public function validate(array $sudoku): array;
}