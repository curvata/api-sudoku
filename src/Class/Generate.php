<?php

namespace App\Class;

use App\Exceptions\LimitSudokuException;
use App\Exceptions\ModeSudokuException;
use App\Interface\GenerateInterface;

Class Generate implements GenerateInterface
{
    CONST MODE = [
        'easy' => 27,
        'medium' => 45,
        'hard' => 63
    ];

    CONST LIMIT = 10;

    /**
     * Génère jusqu'à LIMIT grilles de sudoku
     */
    public function generate(string $mode, int $many): array
    {
        if (isset(SELF::MODE[$mode])) {
            $sudoku = [];
            if ($many <= 10 && $many > 0) {
                for ($n=0; $n < $many; $n++) {
                    $rows = $this->constructRows($this->generateFirstRow());
                    $withMode = $this->createMode($mode, $rows);
                    $shuffles = $this->shuffleColumns($this->shuffleRows($withMode));
                    $sudoku [$n] = $this->createGrids($shuffles);
                }
                return $sudoku;
            } else {
                throw new LimitSudokuException(self::LIMIT);
            }
        } else {
            throw new ModeSudokuException();
        }
    }

    /**
     * Génère la première ligne
     */
    private function generateFirstRow(): array
    { 
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $row = [];

        for ($n=0; $n<9; $n++) {
            $a = random_int(0, count($numbers)-1); 
            $row[$n] = $numbers[$a];
            array_splice($numbers, $a, 1);
        }

        return $row;
    }
    
    /**
     * Créer les lignes à partir de la première
     */
    private function constructRows(array $row): array
    {
        $sudoku = [];

        for ($x=0; $x<7; $x+=3) {
            for ($a=0; $a<3; $a++) {
                $index = 0;
                for ($b=0; $b<3; $b++) {
                    for ($c=0; $c<3; $c++) {
                        $sudoku[$b+$x][] = $row[$index];

                        $index++;
                    }
                }
                ($a != 2)? $row = $this->moveRow($row, 3) : "";
                $index = 0;
            }
            $row = $this->moveRow($row, 1);
        }

        return $sudoku;
    }

    /**
     * Déplace les éléments du tableau de x index
     */
    private function moveRow(array $row, int $move)
    {
        $array = [];

        for ($n=0; $n<9; $n++) {
            if ($n > 5 && $move === 3) {
                $array[$n] = $row[($n-6)];
            } else if ($n === 8 && $move === 1) {
                $array[$n] = $row[0];
            } else {
                $array[$n] = $row[($n+$move)];
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
     * Mélanger les lignes
     */
    private function shuffleRows(array $sudoku): array
    {
        $array = array_chunk($sudoku, 3);

        foreach ($array as $k => $v) {
            shuffle($array[$k]);
        }

        return array_merge($array[0], $array[1], $array[2]);
    }

    /**
     * Mélanger les colonnes
     */
    private function shuffleColumns(array $sudoku): array
    {
        $array = [];

        for ($a=0; $a<9; $a++) {
            $array[] = array_column($sudoku, $a);
        }

        $columns = array_chunk($array, 3);

        foreach ($columns as $k => $v) {
            shuffle($columns[$k]);
        }

        $columns = array_merge($columns[0], $columns[1], $columns[2]);

        for ($a=0; $a<9; $a++) {
            $sudokuF[] = array_column($columns, $a);
        }

        return $sudokuF;
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
}