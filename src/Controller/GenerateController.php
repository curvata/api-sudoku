<?php  

namespace App\Controller;

use App\Interface\GenerateInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

Class GenerateController extends AbstractController
{
    /**
     * Génère une grille de sudoku
     */
    #[Route('/api/v1/generate', name: 'generate', methods: ['GET'])]
    public function generate(GenerateInterface $generate)
    {
        
    }
}