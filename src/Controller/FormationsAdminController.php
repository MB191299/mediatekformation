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
class FormationsAdminController extends AbstractController
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
     * Afficher toutes les formations.
     *
     * @Route("/admin", name="formationsadmin")
     * @return Response
     */
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formationsadmin.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Trier les formations.
     *
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="formationsadmin.sort")
     * @param string $champ
     * @param string $ordre
     * @param string $table
     * @return Response
     */
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formationsadmin.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Trouver toutes les formations contenant une valeur.
     *
     * @Route("/admin/formations/recherche/{champ}/{table}", name="formationsadmin.findallcontain")
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formationsadmin.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }


    /**
     * Afficher le formulaire pour ajouter une nouvelle formation.
     *
     * @Route("/admin/formations/ajouter", name="formulaire.affichage")
     * @return Response
     */
    public function afficherFormulaire(): Response
    {
        $tableauCategories = $this->categorieRepository->findAll();
        $tableauPlaylists = $this->playlistRepository->findAll();
        $dateJour = $this->getDateJour();
        return $this->render("pages/formulaire.html.twig", [
            'titre' => null,
            'description' => null,
            'videoId' => null,
            'datejour' => $dateJour,
            'date' => null,
            'categories' => $tableauCategories,
            'categorie' => null,
            'playlists' => $tableauPlaylists,
            'playlist' => null
        ]);
    }

    /**
     * Donne pour la date la date du jour et pour l'heure 23:59.
     */
    public function getDateJour(): string
    {
        return date('Y-m-d');
    }

    /**
     * Supprimer une formation.
     *
     * @Route("/admin/formations/formation/supprimer/{id}", name="formations.delete")
     * @param int $id L'identifiant de la formation à supprimer
     * @return Response
     */
    public function delete($id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation, true);
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formationsadmin.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }


    /**
     * Afficher le formulaire pré-rempli pour modifier une formation.
     *
     * @Route("/admin/formations/modifier/{id}", name="formulaire.modifier")
     * @param type $id L'identifiant de la formation à modifier
     * @return Response
     */
    public function afficherFormulaireRempli($id): Response
    {
        $formation = $this->formationRepository->find($id);
        // dd($formation, "sss");
        $tableauCategories = $this->categorieRepository->findAll();
        $tableauPlaylists = $this->playlistRepository->findAll();
        $titre = $formation->getTitle(); 
        $description = $formation->getDescription();
        $categorie = $formation->getCategories()->toArray();
        $playlist =   $formation->getPlaylist();
        $videoId = $formation->getVideoId();
        $date = $formation->getPublishedAt();
        return $this->render("pages/formulaire.html.twig", [
            'formation' => $formation,
            // 'titre' => $titre,
            // 'description' => $description,
            // 'categorie' => $categorie,
            'categories' => $tableauCategories,
            // 'playlist' => $playlist,
            'playlists' => $tableauPlaylists,
            // 'videoId' => $videoId,
            'datejour' =>  $this->getDateJour(),
            'date' => $date,
            // 'formation_id' => $id
        ]);
    }

    /**
     * Ajoute la nouvelle formation à la BDD
     * 
     * @Route("/admin/formations/formulaire", name="formations.ajouter")
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        // Si l'ID est passé en paramètre, l'ancienne formation est supprimée et une nouvelle est ajoutée.
        $id = $request->request->get('formation_id');

        // Si c'est une création.
        // Récupérer les données du formulaire.
        $title = $request->request->get('titre');
        $description = $request->request->get('description');
        $categorieName = $request->request->all()['categories'];
        $playlistName = $request->request->get('playlists');
        $publishedAt = $request->request->get('date');
        $videoId = $request->request->get('videoId');
        if ($id == null) {
            $newFormation = new Formation();
        } else {
            $newFormation = $this->formationRepository->find($request->request->get('formation_id'));
        }

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
        $date = new DateTime($publishedAt);
        $newFormation->setPublishedAt($date);

        // Ajouter la formation à la base de données.
        $this->formationRepository->add($newFormation, true);

        return $this->render("pages/formation.html.twig", [
            'formation' => $newFormation,
        ]);
    }
}
