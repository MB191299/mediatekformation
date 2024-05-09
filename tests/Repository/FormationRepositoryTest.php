<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test sur toutes les methodes de FormationRepository
 *
 * @author Myriam Bloch
 */
class FormationRepositoryTest extends KernelTestCase
{

    /** Récupération du repository de Formation
     * @return FormationRepository
     */

    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    public function testNbFormations()
    {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(3, $nbFormations);
    }

    /**
     * Création d'une instance de Formation avec titre, playlist, videoId
     * @return Formation
     */
    public function newFormation(): Formation
    {
        $formation = (new Formation())
            ->setPlaylist(null, 'Eclipse et java')
            ->setTitle("formation")
            ->setVideoId("https://youtu.be/XgVADKKb4jI");
        return $formation;
    }


    /**
     * Fonction de test d'ajout d'une formation
     */
    public function testAddFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }
}
