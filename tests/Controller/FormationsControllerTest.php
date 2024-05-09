<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test des filtres de FormationsController
 *
 * @author Myriam Bloch
 */
class FormationsControllerTest extends WebTestCase
{

    public function testAccesPage()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    /**
     * test du filtre de recherche des formations par nom de formation
     */
    public function testFiltreFormation()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(19, $crawler->filter('h5'));
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Python');
    }

    /**
     * test du filtre de recherche des formations par nom de playlist
     */
    public function testFiltrePlaylist()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/name/playlist');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(10, $crawler->filter('h5'));
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    /**
     * test du filtre de recherche des formations par nom de categorie
     */
    public function testFiltreCategorie()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/id/categories');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(27, $crawler->filter('h5'));
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Cours Merise/2 extensions');
    }

    /**
     * test du filtre de recherche des formations par ordre alphabetique ASC
     */
    public function testTriFormationsAsc()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    /**
     * test du filtre de recherche des formations par ordre alphabetique DESC du nom de playlist
     */
    public function testTriPlaylistDesc()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
    }

    /**
     * test du filtre de recherche des formations par date de publication ASC
     */
    public function testTriDateAsc()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        //verifie si la premiere formation correspond a la recherche
        $this->assertSelectorTextContains('h5', "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }

    /**
     * test du lien d'une formation 
     */
    public function testLinkFormation()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        //clic sur un lien (details de la formation)
        $client->clickLink('image-formation-miniature');
        //controle si le lien existe
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //recuperation de la route et controle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
        $this->assertSelectorTextContains('h4', "Eclipse n°8 : Déploiement");
    }
}
