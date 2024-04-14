<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController
{

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;



    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository, PlaylistRepository $playlistRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    /**
     * * Affiche toutes les formations.
     * 
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les formations selon le champ spécifié.
     *
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param string $champ Le champ selon lequel trier les formations
     * @param string $ordre L'ordre de tri (ASC ou DESC)
     * @param string $table La table dans laquelle effectuer le tri
     * @return Response
     */
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche les formations selon la valeur spécifiée.
     *
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param string $champ Le champ dans lequel effectuer la recherche
     * @param Request $request La requête HTTP contenant la valeur de recherche
     * @param string $table La table dans laquelle effectuer la recherche
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche une seule formation.
     *
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param int $id L'identifiant de la formation à afficher
     * @return Response
     */
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }
}
