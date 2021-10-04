<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidateControllerTest extends WebTestCase
{
    public function testValidateOk()
    {
        $sudoku =  
        [
            [
                [2, 5, 1, 6, 7, 9, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [4, 3, 2, 5, 1, 6, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4],
                [1, 6, 7, 9, 8, 4, 3, 2, 5],
            ]
        ];

        $client = $this->createClient(); 
        $client->request(
            "POST", 
            "/api/v1/validate", 
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($sudoku)
        );

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData["success"]);
        $this->assertTrue($responseData["data"][0]);
    }

    public function testValidateNotOk()
    {
        $sudoku =  
        [
            [
                [2, 5, 1, 6, 7, 7, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [4, 3, 2, 5, 1, 6, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4],
                [1, 6, 7, 9, 8, 4, 3, 2, 5],
            ]
        ];

        $client = $this->createClient(); 
        $client->request(
            "POST", 
            "/api/v1/validate", 
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($sudoku)
        );

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData["success"]);
        $this->assertFalse($responseData["data"][0]);
    }

    public function testValidateNotOk2()
    {
        $sudoku =  
        [
            [
                [2, 5, 1, 6, 7, 7, 8, 4, 3], 
                [6, 7, 9, 8, 4, 3, 2, 5, 1], 
                [8, 4, 3, 2, 5, 1, 6, 7, 9], 
                [4, 3, 2, 5, 1, 6, 7, 9, 8], 
                [5, 1, 6, 7, 9, 8, 4, 3, 2], 
                [7, 9, 8, 4, 3, 2, 5, 1, 6], 
                [9, 8, 4, 3, 2, 5, 1, 6, 7], 
                [3, 2, 5, 1, 6, 7, 9, 8, 4],
            ]
        ];

        $client = $this->createClient(); 
        $client->request(
            "POST", 
            "/api/v1/validate", 
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($sudoku)
        );

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Votre grille n°1 n'est pas conforme !", $responseData["data"][0]);
    }

    public function testValideToMany()
    {
        $sudoku =  
        [
            [], [],[],[],[],[],[],[],[],[],[],
        ];

        $client = $this->createClient(); 
        $client->request(
            "POST", 
            "/api/v1/validate", 
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($sudoku)
        );

        $response = $client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString("La limite est de 10 grilles", $responseData["message"]);
    }
}
