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
     * 
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
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
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
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
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
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }

    /**
     * @Route("/formations/formation/supprimer/{id}", name="formations.delete")
     * @param type $id
     * @return Response
     */
    public function delete($id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation, false);
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/formations/ajouter", name="formulaire.affichage")
     * @return Response
     */
    public function afficherFormulaire(): Response
    {
        $tableauCategories = $this->categorieRepository->findAll();
        $tableauPlaylists = $this->playlistRepository->findAll();
        return $this->render("pages/formulaire.html.twig", [
            'titre' => null,
            'description' => null,
            'videoId' => null,
            'date' => null,
            'categories' => $tableauCategories,
            'playlists' => $tableauPlaylists,
            'playlist' => null
        ]);
    }

    /**
     * @Route("/formations/modifier/{id}", name="formulaire.modifier")
     * @param type $id
     * @return Response
     */
    public function afficherFormulaireRempli($id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $tableauCategories = $this->categorieRepository->findAll();
        $tableauPlaylists = $this->playlistRepository->findAll();
        $titre = $formation->getTitle();
        $description = $formation->getDescription();
        $categorie = $formation->getCategories()->toArray();
        $playlist = $formation->getPlaylist()->getName();
        $videoId = $formation->getVideoId();
        $date = $formation->getPublishedAt();
        return $this->render("pages/formulaire.html.twig", [
            'titre' => $titre,
            'description' => $description,
            'categorie' => $categorie,
            'categories' => $tableauCategories,
            'playlist' => $playlist,
            'playlists' => $tableauPlaylists,
            'videoId' => $videoId,
            'date' => $date,
        ]);
    }

    //ajouter la nouvelle formation à la BDD
    //faire pareil pour supprimer
    /**
     * @Route("/formations/formulaire", name="formations.ajouter")
     * @return Response
     */
    public function ajouter(Request $request)
    {
        // dd($request->request->all());
        if ($request->isMethod('POST')) {
            // Récupérez les données du formulaire
            $title = $request->request->get('titre');
            $description = $request->request->get('description');
            $categorieName = $request->request->all()['categories'];
            $playlistName = $request->request->get('playlists');
            $publishedAt = $request->request->get('date');
            $videoId = $request->request->get('videoId');
        }

        $newFormation = new Formation();

        if ($title == null) {
            return $this->afficherFormulaire();
        }
        $newFormation->setTitle($title);
        $newFormation->setDescription($description);
        foreach ($categorieName as $categorie) {
            $categories = $this->categorieRepository->find(intval($categorie));
            $newFormation->addCategory($categories);
        }

        $playlist = $this->playlistRepository->find(intval($playlistName));
        $newFormation->setPlaylist($playlist);
        $newFormation->setVideoId($videoId);
        $newFormation->getMiniature();
        // $newFormation->getPicture();
        // $dateJour = strtotime($publishedAt);
        // if ($dateEntree == $dateJour) { }
        $date = new DateTime($publishedAt);
        $newFormation->setPublishedAt($date);

        $this->formationRepository->add($newFormation, true);
        return $this->render("pages/formation.html.twig", [
            'formation' => $newFormation,
        ]);
        //ajouter la nouvelle formation dans les playlits et les categories
    }
}
