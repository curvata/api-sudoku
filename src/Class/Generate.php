<?php

namespace App\Class;

use App\Exceptions\LimitSudokuException;
use App\Exceptions\ModeSudokuException;
use App\Interface\GenerateInterface;
use Exception;

Class Generate implements GenerateInterface
{
    CONST MODE = [
        'EASY' => 27,
        'MEDIUM' => 45,
        'HARD' => 63
    ];

    CONST LIMIT = 10;

    /**
     * Génère jusqu'à  grilles de sudoku
     */
    public function generate(string $mode, int $many): array
    {
        if (isset(SELF::MODE[$mode])) {
            $sudoku = [];
            if ($many <= 10 && $many > 0) {
                for ($n=0; $n < $many; $n++) {
                    $lines = $this->constructLines($this->generateFirstLine());
                    $withMode = $this->createMode($mode, $lines);
                    $sudoku [$n] = $this->createGrids($this->shuffleLines($withMode));
                }
                return $sudoku;
            } else {
                throw new LimitSudokuException();
            }
        } else {
            throw new ModeSudokuException();
        }
    }
    
    /**
     * Créer les lignes à partir de la première
     */
    private function constructLines(array $line): array
    {
        $sudoku = [];
        $index = 0;

        for ($x=0; $x<7; $x+=3) {
            for ($a=0; $a<3; $a++) {
                for ($b=0; $b<3; $b++) {
                    for ($c=0; $c<3; $c++) {
                        $sudoku[$b+$x][] = $line[$index];
                        $index++;
                    }
                }
                ($a != 2)? $line = $this->moveLine($line, 3) : "";
                $index = 0;
            }
            $line = $this->moveLine($line, 1);
        }

        return $sudoku;
    }
    
    /**
     * Génère la première ligne
     */
    private function generateFirstLine(): array
    { 
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $line = [];

        for ($n=0; $n<9; $n++) {
            $a = random_int(0, count($numbers)-1); 
            $line[$n] = $numbers[$a];
            array_splice($numbers, $a, 1);
        }

        return $line;
    }
    
    /**
     * Déplace les éléments du tableau de x index
     */
    private function moveLine(array $line, int $move)
    {
        $array = [];

        for ($n=0; $n<9; $n++) {
            if ($n > 5 && $move === 3) {
                $array[$n] = $line[($n-6)];
            } else if ($n === 8 && $move === 1) {
                $array[$n] = $line[0];
            } else {
                $array[$n] = $line[($n+$move)];
            }
        }
        return $array;    
    }
    
    /**
     * Création des trous dans la grille selon le mode
     */
    private function createMode(string $mode, array $sudoku): array
    {
        $index = [];

        for ($n=0; $n<9; $n++) {
            for ($x=0; $x<9; $x++) {
                $index [$n][] = $x;
            }
        }    
        
        for ($x=0; $x<self::MODE[$mode]; $x++) {
            $ok = false;

            while ($ok === false) {
                $key = random_int(0, 8);
                $count = (count($index[$key])-1);

                if ($count > 1) {
                    $a = random_int(0, (count($index[$key])-1));
                    $sudoku[$key][$index[$key][$a]] = '*';
                    array_splice($index[$key], $a, 1);
                    $ok = true;
                } else {
                    $ok = false;
                }
            }
        }

        return $sudoku;
    }
    
    /**
     * Création des grilles à partir des lignes générées
     */
    private function createGrids(array $sudoku): array
    {
        $sudokuF = [];
        $index = 0;

        for ($x=0; $x<7; $x+=3) {
            for ($a=$x; $a<3+$x; $a++) {
                for ($b=0; $b<3; $b++) {
                    for ($c=0; $c<3; $c++) {
                        $sudokuF[$b+$x][] = $sudoku[$a][$index];
                        $index++;
                    }
                }
                $index = 0;
            }
        }

        return $sudokuF;
    }
    
    /**
     * Mélanger les lignes
     */
    private function shuffleLines(array $sudoku): array
    {
        $array = array_chunk($sudoku, 3);

        foreach ($array as $k => $v) {
            shuffle($array[$k]);
        }

        return array_merge($array[0], $array[1], $array[2]);
    }
}