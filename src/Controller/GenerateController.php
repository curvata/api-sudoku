<?php  

namespace App\Controller;

use App\Interface\GenerateInterface;
use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

Class GenerateController extends AbstractController
{
    /**
     * Générer des grilles de sudoku
     */
    #[Route('/api/v1/generate', name: 'generate', methods: ['GET'])]
    public function generate(GenerateInterface $generate, Request $request): JsonResponse
    {
        $time = (new DateTime("now", (new DateTimeZone('Europe/Paris'))))->format("d-m-Y H:i:s");
        $content = $request->query->all();
        
        $config = [
            'Content-Type' => "application/json",
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET'
        ];

        if (!array_key_exists("many", $content) || !array_key_exists("mode", $content)) {
            return new JsonResponse(
                ["success" => false, "created_at" => $time, "Message" => "Merci d'utiliser des paramètres de configuration valide !"], 
                400, 
                $config);
        }

        try {
            $sudoku["success"] = true;
            $sudoku["created_at"] = $time;
            $sudoku["data"] = $generate->generate((string)$content["mode"], (int)$content["many"]);
        } catch (Exception $e) {
            $sudoku["success"] = false;
            $sudoku["message"] = $e->getMessage();
        }
        
        return new JsonResponse($sudoku, 200, $config);
    }
}