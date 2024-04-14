<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    /**
     * Création d'une instance de Playlist avec nom
     * @return Playlist
     */
    public function newPlaylist(): Playlist
    {
        $formation = (new Playlist())
            ->setDescription("nouvelle playlist")
            ->setName("playlist");
        return $formation;
    }

    /** Récupération du repository de Playlist
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    public function testAddPlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemovePlaylist()
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression");
    }
}
