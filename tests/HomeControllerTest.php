<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = $this->createClient(); 
        $crawler = $client->request("GET", "/");
        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            1,
            $crawler->filter('h2:contains("SUDOKU")')->count());
        $this->assertEquals(
            1,
            $crawler->filter('h2:contains("CONFIGURATION GENERATE")')->count());
        $this->assertEquals(
            1,
            $crawler->filter('h2:contains("CONFIGURATION VALIDATE")')->count());

    }
}
