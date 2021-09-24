<?php  

namespace App\Controller;

use App\Interface\ValidateInterface;
use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

Class ValidateController extends AbstractController
{
    /**
     * Valider des grilles de sudoku
     */
    #[Route('/api/v1/validate', name: 'validate', methods: ['POST'])]
    public function generate(ValidateInterface $validate, Request $request): JsonResponse
    {
        $time = new DateTime("now", (new DateTimeZone('Europe/Paris')));
        $content = $request->request->all();

        $config = [
            'Content-Type' => "application/json",
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST'
        ];

        try {
            $sudoku["success"] = true;
            $sudoku["date"] =  $time->format("d-m-Y H:i:s");
            $sudoku["data"] = $validate->validate($content);
        } catch (Exception $e) {
            $sudoku["success"] = false;
            $sudoku["message"] = $e->getMessage();
        }

        return new JsonResponse($sudoku, 200, $config);
    }
}