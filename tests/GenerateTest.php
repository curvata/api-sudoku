<?php

namespace App\Tests;

use App\Class\Generate;
use App\Exceptions\LimitSudokuException;
use App\Exceptions\ModeSudokuException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateTest extends KernelTestCase
{
    use InvokeMethod;

    public function testGenerate()
    {
        $generate = new Generate();
        $sudoku = $generate->generate("hard", 1);

        foreach ($sudoku[0] as $k => $v) {
            $sudoku[0] = array_merge($sudoku[0], $sudoku[0][$k]);
        }

        $this->assertCount(90, $sudoku[0]);

        $count = 0;

        foreach ($sudoku[0] as $k => $v) {
            ("*" === $v)? $count++ : "";
        }

        $this->assertEquals(63, $count);
    }

    public function testManyGenerate()
    {
        $generate = new Generate();
        $sudoku = $generate->generate("hard", 10);

        $this->assertCount(10, $sudoku);
        $this->assertCount(9, $sudoku[0]);
    }

    public function testConstructLines()
    {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $generate = new Generate();
        $lines = $this->invokeMethod($generate, "constructRows", [$array]);

        $this->assertCount(9, $lines);
        $this->assertEquals(456789123, implode("", $lines[1]));
        $this->assertEquals(891234567, implode("", $lines[3]));
    }

    public function testModeNotValid(): void
    {
        $this->expectException(ModeSudokuException::class);

        $sudoku = new Generate();
        $sudoku->generate("veteran", 1);
    }

    public function testManyNotValid(): void
    {
        $this->expectException(LimitSudokuException::class);

        $sudoku = new Generate();
        $sudoku->generate("hard", 11);
    }

    public function testGenerateFirstLine()
    {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $generate = new Generate();
        $line = $this->invokeMethod($generate, "generateFirstRow", []);

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
        $shuffles = $this->invokeMethod($generate, "shuffleRows", [$sudoku]);
        $ok = false;

        foreach ($shuffles as $k => $v) {
            ($shuffles[$k] != $sudoku[$k])? $ok = true : "";
        }

        $this->assertGreaterThanOrEqual(1, $ok);
        $this->assertEquals(20, $sudoku[3]+$sudoku[4]+$sudoku[5]);
    }

    public function testShuffleColumns()
    {
        $sudoku = [
            [8, 4, 3, 2, 5, 1, 6, 7, 9], 
            [2, 5, 1, 6, 7, 9, 8, 4, 3], 
            [6, 7, 9, 8, 4, 3, 2, 5, 1], 
            [7, 9, 8, 4, 3, 2, 5, 1, 6], 
            [4, 3, 2, 5, 1, 6, 7, 9, 8], 
            [5, 1, 6, 7, 9, 8, 4, 3, 2], 
            [1, 6, 7, 9, 8, 4, 3, 2, 5], 
            [9, 8, 4, 3, 2, 5, 1, 6, 7], 
            [3, 2, 5, 1, 6, 7, 9, 8, 4]
        ];

        $generate = new Generate();
        $shuffles = $this->invokeMethod($generate, "shuffleColumns", [$sudoku]);
        $ok = false;

        foreach ($shuffles as $k => $v) {
            foreach ($v as $key => $value) {
                ($shuffles[$k] != $sudoku[$k][$key])? $ok = true : "";
            }
        }

        $this->assertGreaterThanOrEqual(1, $ok);
        $this->assertEquals(14, $sudoku[8][3]+$sudoku[8][4]+$sudoku[8][5]);

    }

    public function testMoveLine()
    {
        $sudoku = [1, 4, 5, 9, 8, 7, 6, 3, 2];
        $generate = new Generate();

        // Move 3
        $move = $this->invokeMethod($generate, "moveRow", [$sudoku, 3]); 
        $this->assertTrue(([9, 8, 7, 6, 3, 2, 1, 4, 5] === $move));

        // Move 1
        $move = $this->invokeMethod($generate, "moveRow", [$sudoku, 1]); 
        $this->assertTrue(([4, 5, 9, 8, 7, 6, 3, 2, 1] === $move));
    }

    public function testGenerateMode()
    {
        $sudoku = [];

        $generate = new Generate();
        $mode = $this->invokeMethod($generate, "createMode", ['medium', $sudoku]);

        foreach ($mode as $k => $v) {
            $sudoku = array_merge($sudoku, $mode[$k]);
        }

        $this->assertCount(45, $sudoku);
    }

    public function testGenerateFinalGrid()
    {
        $sudoku = [
            [8, 4, 3, 2, 5, 1, 6, 7, 9], 
            [2, 5, 1, 6, 7, 9, 8, 4, 3], 
            [6, 7, 9, 8, 4, 3, 2, 5, 1], 
            [7, 9, 8, 4, 3, 2, 5, 1, 6], 
            [4, 3, 2, 5, 1, 6, 7, 9, 8], 
            [5, 1, 6, 7, 9, 8, 4, 3, 2], 
            [1, 6, 7, 9, 8, 4, 3, 2, 5], 
            [9, 8, 4, 3, 2, 5, 1, 6, 7], 
            [3, 2, 5, 1, 6, 7, 9, 8, 4]
        ];

        $generate = new Generate();
        $grid = $this->invokeMethod($generate, "createGrids", [$sudoku]);  

        $this->assertEquals(8, $grid[7][1]);
        $this->assertEquals(24, ($grid[5][3] + $grid[5][4] + $grid[5][5]));
        $this->assertEquals(12, ($grid[3][6] + $grid[3][7] + $grid[3][8]));
    }
}
