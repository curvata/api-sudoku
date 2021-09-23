<?php

namespace App\Tests;

use App\Class\Validate;
use App\Exceptions\LimitSudokuException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ValidateTest extends KernelTestCase
{
    use InvokeMethod;

    public function testValidateOk()
    {
        $sudoku = [
            [
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [2, 5, 1, 6, 7, 9, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [4, 3, 2, 5, 1, 6, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [1, 6, 7, 9, 8, 4, 3, 2, 5], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4]
            ]
        ];

        $validate = new Validate();

        $bool = $this->invokeMethod($validate, "validate", [$sudoku]);
        $this->assertTrue($bool[0]);
    }

    public function testValidateNotOk()
    {
        $sudoku = [
            [
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [2, 5, 1, 6, 7, 9, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [4, 3, 2, 5, 1, 1, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [1, 6, 7, 9, 8, 4, 3, 2, 5], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4]
            ]
        ];

        $validate = new Validate();

        $bool = $this->invokeMethod($validate, "validate", [$sudoku]);
        $this->assertFalse($bool[0]);
    }

    public function testValidNotConform()
    {
        $sudoku = [
            [
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [2, 5, 1, 6, 7, 9, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [4, 3, 2, 5, 1, 1, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [1, 6, 7, 9, 8, 4, 3, 2, 5], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4]
            ],
            [
                [8, 4, "*", 2, 5, 1, 6, 7, 9], 
                [2, 5, 1, 6, 7, 9, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [4, 3, 2, 5, 1, 1, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [1, 6, 7, 9, 8, 4, 3, 2, 5], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4]
            ]
        ];

        $validate = new Validate();

        $conform = $this->invokeMethod($validate, "validate", [$sudoku]);
        $this->assertStringContainsString($conform[1], "Votre grille n°2 n'est pas conforme !");
    }

    public function testLimitNotValid(): void
    {
        $this->expectException(LimitSudokuException::class);

        $sudoku = [[],[],[],[],[],[],[],[],[],[],[]];

        $validate = new Validate();
        $validate->validate($sudoku);
    }

    public function testTransformInRows()
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

        $validate = new Validate();

        $sudoku = $this->invokeMethod($validate, "transformInRows", [$sudoku]);

        $this->assertCount(27, $sudoku);
        $this->assertEquals(9, $sudoku[6][3]);
        $this->assertEquals(4, $sudoku[8][8]);
        $this->assertEquals(6, $sudoku[13][5]);
        $this->assertEquals(4, $sudoku[14][6]);
        $this->assertEquals(3, $sudoku[20][0]);
        $this->assertEquals(3, $sudoku[24][6]);
    }

    public function testIsSudoku()
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

        $validate = new Validate();

        $this->assertTrue($this->invokeMethod($validate, "isSudoku", [$sudoku]));
    }

    public function testIsNotSudoku()
    {
        $sudoku = [
            [8, 4, 3, 2, 5, 1, 6, 7, 9, 9], 
            [2, 5, 1, 6, 7, 9, 8, 4, 3], 
            [6, 7, 9, 8, 4, 3, 2, 5, 1], 
            [7, 9, 8, 4, 3, 2, 5, 1, 6], 
            [4, 3, 2, 5, 1, 6, 7, 9, 8], 
            [5, 1, 6, 7, 9, 8, 4, 3, 2], 
            [1, 6, 7, 9, 8, 4, 3, 2, 5], 
            [9, 8, 4, 3, 2, 5, 1, 6, 7], 
            [3, 2, 5, 1, 6, 7, 9, 8, 4] 
        ];

        $sudoku2 = [[], [], [], [], [], [], [], [], [], []];

        $validate = new Validate();

        $this->assertFalse($this->invokeMethod($validate, "isSudoku", [$sudoku]));
        $this->assertFalse($this->invokeMethod($validate, "isSudoku", [$sudoku2]));
    }

    public function testValidRows()
    {
        $sudoku = [
            [8, 4, 3, 5, 2, 1, 6, 7, 9]
        ];

        $validate = new Validate();

        $this->assertTrue($this->invokeMethod($validate, "validRows", [$sudoku]));
    
    }

    public function testNotValidRows()
    {
        $sudoku = [
            [8, 4, 3, 3, 1, 2, 6, 7, 9]
        ];

        $validate = new Validate();

        $this->assertFalse($this->invokeMethod($validate, "validRows", [$sudoku]));
    
    }
}
