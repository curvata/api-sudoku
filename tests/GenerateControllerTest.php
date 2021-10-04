<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenerateControllerTest extends WebTestCase
{
    public function testGenerateNotOk()
    {
        $client = $this->createClient(); 
        $client->request("GET", "/api/v1/generate");
        $response = $client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Merci d'utiliser des paramètres de configuration valide !", $responseData["message"]);
        $this->assertFalse($responseData["success"]);
    }

    public function testGenerateOneGrid()
    {
        $client = $this->createClient(); 
        $client->request("GET", "/api/v1/generate?many=1&mode=easy");
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData["data"]);
        $this->assertCount(9, $responseData["data"][0]);
    }

    public function testGenerateManyGrids()
    {
        $client = $this->createClient(); 
        $client->request("GET", "/api/v1/generate?many=10&mode=easy");
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(10, $responseData["data"]);
        $this->assertCount(9, $responseData["data"][8]);
    }

    public function testGenerateToMany()
    {
        $client = $this->createClient(); 
        $client->request("GET", "/api/v1/generate?many=11&mode=easy");
        $response = $client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString("La limite est de 10 grilles", $responseData["message"]);
    }
}
