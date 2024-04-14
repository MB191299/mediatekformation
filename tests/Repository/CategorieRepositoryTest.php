<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Test des methodes de CategorieRepository
 *
 * @author Myriam Bloch
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Création d'une instance de Categorie avec nom
     * @return Categorie
     */
    public function newCategorie(): Categorie
    {
        $categorie = (new Categorie())
            ->setName("categorie");
        return $categorie;
    }

    /** Récupération du repository de Categorie
     * @return CategorieRepository
     */
    public function recupRepository(): CategorieRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Teste la methode d'ajout d'une categorie
     */
    public function testAddCategorie()
    {
        $repository = $this->recupRepository();
        $Categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($Categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    /**
     * Teste la methode de suppression d'une categorie
     */
    public function testRemoveCategorie()
    {
        $repository = $this->recupRepository();
        $Categorie = $this->newCategorie();
        $repository->add($Categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($Categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression");
    }
}
