<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiDocTest extends WebTestCase
{
    public function testGetSwagger200(): void
    {
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            'http://127.0.0.1:8000/api/docs',
            [],    // paramètres GET
            [],    // fichiers
            ['HTTP_ACCEPT' => 'text/html'] // en-têtes
        );
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Hello API Platform - API Platform');
    }

    public function testGetOtherThanSwagger404(){
        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            'http://127.0.0.1:8000/f',
            [],    // paramètres GET
            [],    // fichiers
            ['HTTP_ACCEPT' => 'text/html'] // en-têtes
        );
        $this->assertResponseStatusCodeSame(404);
    }
}
