<?php

namespace App\Interface;

Interface GenerateInterface
{
    public function generate(string $mode, int $many): array;
}