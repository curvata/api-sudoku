<?php

namespace App\Tests;

use App\Class\Generate;
use App\Exceptions\BadModeSudokuException;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateTest extends KernelTestCase
{
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testModeNotValid(): void
    {
        $this->expectException(BadModeSudokuException::class);
        $sudoku = new Generate();
        $array = $sudoku->generate("VETERAN");
    }

    public function testGenerateFirstLine()
    {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $generate = new Generate();
        $line = $this->invokeMethod($generate, "generateFirstLine", []);
        $this->assertCount(9, $line);
        $this->assertEmpty(array_diff($array, $line));
    }

    public function testShuffleLines()
    {
        $sudoku = [
            0 => 1,
            1 => 7,
            2 => 120,
            3 => 10,
            4 => 5,
            5 => 5,
            6 => 16,
            7 => 17,
            8 => 18,
        ];

        $generate = new Generate();
        $shuffles = $this->invokeMethod($generate, "shuffleLines", [$sudoku]);
        $ok = false;

        foreach ($shuffles as $k => $v) {
            ($shuffles[$k] != $sudoku[$k])? $ok = true : "";
        }

        $this->assertGreaterThanOrEqual(1, $ok);
        $this->assertEquals(20, $sudoku[3]+$sudoku[4]+$sudoku[5]);
    }

    public function testMoveLine()
    {
        $sudoku = [1, 4, 5, 9, 8, 7, 6, 3, 2];
        $generate = new Generate();

        // Move 3
        $move = $this->invokeMethod($generate, "moveLine", [$sudoku, 3]); 
        $this->assertTrue(([9, 8, 7, 6, 3, 2, 1, 4, 5] === $move));

        // Move 1
        $move = $this->invokeMethod($generate, "moveLine", [$sudoku, 1]); 
        $this->assertTrue(([4, 5, 9, 8, 7, 6, 3, 2, 1] === $move));
    }

    public function testGenerateMode()
    {

        $sudoku = [];
        $generate = new Generate();

        $mode = $this->invokeMethod($generate, "generateMode", ['MEDIUM', $sudoku]);

        foreach ($mode as $k => $v) {
            $sudoku = array_merge($sudoku, $mode[$k]);
        }

        $this->assertCount(45, $sudoku);
    }

    public function testGenerateFinalGrid()
    {
        $sudoku = [
            [8, 4, 3, 2, 5, 1, 6, 7, 9], 
            [2, 5, 4, 6, 7, 9, 8, 4, 3], 
            [6, 7, 9, 8, 4, 3, 2, 5, 4], 
            [7, 9, 8, 4, 3, 2, 5, 4, 6], 
            [4, 3, 2, 5, 4, 6, 7, 9, 8], 
            [5, 4, 6, 7, 9, 8, 4, 3, 2], 
            [4, 6, 7, 9, 8, 4, 3, 2, 5], 
            [9, 8, 4, 3, 2, 5, 4, 6, 7], 
            [3, 2, 5, 4, 6, 7, 9, 8, 4]
        ];

        $generate = new Generate();

        $grid = $this->invokeMethod($generate, "generateFinalGrid", [$sudoku]);  

        $this->assertEquals(8, $grid[7][1]);
        $this->assertEquals(24, ($grid[5][3] + $grid[5][4] + $grid[5][5]));
        $this->assertEquals(15, ($grid[3][6] + $grid[3][7] + $grid[3][8]));
    }
}
