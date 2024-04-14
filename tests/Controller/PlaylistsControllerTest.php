<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test des filtres de CategoriesController
 *
 * @author Myriam Bloch
 */
class PlaylistsControllerTest extends WebTestCase
{
    /**
     * test du filtre de recherche des playlists par nom de playlist
     */
    public function testFiltrePlaylist()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/name');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        //verifie si la premiere playlist correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    /**
     * test du filtre de recherche des playlists par ordre alphabetique DESC
     */
    public function testTriPlaylistsDesc()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/DESC');
        //verifie si la premiere playlist correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');
    }

    /**
     * test du filtre de recherche des playlists par nom de categorie
     */
    public function testFiltreCategorie()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/id/categories');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'C#'
        ]);
        //verifie le nombre de lignes obtenues
        $this->assertCount(2, $crawler->filter('h5'));
        //verifie si la premiere playlist correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    /**
     * test du filtre de recherche des playlists par nb de formations ASC
     */
    public function testTriNbFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/nbformations/ASC');
        //verifie si la premiere playlist correspond a la recherche
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }

    /**
     * test du lien "voir details" d'une playlist
     */
    public function testLinkPlaylist()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        //clic sur un lien (details de la formation)
        $client->clickLink('Voir détail');
        //controle si le lien existe
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //recuperation de la route et controle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
        $this->assertSelectorTextContains('h4', "Bases de la programmation (C#)");
    }
}
