<?php

namespace App\Class;

use App\Exceptions\LimitSudokuException;
use App\Interface\ValidateInterface;

Class Validate implements ValidateInterface
{
    CONST LIMIT = 10;
    
    /**
     * Valider jusqu'à LIMIT grilles de sudoku
     */
    public function validate(array $sudoku): array
    {
        $many = count($sudoku);
        $validate = [];

        if ($many <= self::LIMIT && $many > 0) {
            foreach ($sudoku as $k => $v) {
                if (!$this->isSudoku($v)) {
                    $validate[$k] = "Votre grille n°". $k+1 ." n'est pas conforme !";
                } else {
                    $rows = $this->transformInRows($v);
                    $validate[$k] = $this->validRows($rows);
                }
            } 
            return $validate;
        } else {
            throw new LimitSudokuException(self::LIMIT);
        }
    }
    
    /**
     * Transforme les grilles en lignes
     */
    private function transformInRows(array $sudoku): array
    {
        $rows = [];
        $key = 0;

        for ($a=0; $a<7; $a+=3) {
            for ($b=0; $b<7; $b+=3) {
                for ($c=0; $c<3; $c++) {
                    for ($d=(0+$b); $d<(3+$b); $d++) {
                        $rows["Rows"][$key][] = $sudoku[$c+$a][$d];
                    }
                }
                $key++;
            }
        }

        foreach ($rows["Rows"] as $k => $v) {
            for ($a=0; $a<9; $a++) {
                $rows["Columns"][$a][] = $v[$a];
            }
        }

        return array_merge($sudoku, $rows['Rows'], $rows['Columns']);
    }
    
    /**
     * Vérifie que toutes les lignes soient valides
     */
    private function validRows(array $rows): bool
    {
        foreach ($rows as $k => $v) {
            foreach ($v as $value) {
                if (count(array_keys($rows[$k], $value)) > 1) {
                    return false;
                }
            }
        }

        return true;
    }
    
    /**
     * Vérifie que la grille est bien une grille de sudoku
     */
    private function isSudoku(array $sudoku): bool
    {
        if (count($sudoku) != 9) {
            return false;
        }

        for ($a=0; $a<9; $a++) {
            if (count($sudoku[$a]) != 9) {
                return false;
            }

            foreach ($sudoku[$a] as $v) {
                if ((int)$v <= 0 || (int)$v > 9) {
                    return false;
                }
            }
        }

        return true;
    }
}
