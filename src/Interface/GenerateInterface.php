<?php

namespace App\Interface;

use Exception;

Interface GenerateInterface
{
    public function generate(string $mode, int $many): array;
}