<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests du controller AccueilController
 *
 * @author Myriam Bloch
 */
class AccueilControllerTest extends WebTestCase
{
    /**
     * Test d'acces a la page d'accueil
     */
    public function testAccesPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}
