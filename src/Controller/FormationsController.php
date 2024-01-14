<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
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
    public function delete($id): void
    {
        $formation = $this->formationRepository->find($id);
        $formationToDelete = $this->formationRepository->remove($formation);
    }

    /**
     * @Route("/formations/ajouter", name="formulaire.affichage")
     * @return Response
     */
    public function afficherFormulaire(): Response
    {
        $tableauCategories = $this->categorieRepository->findAll();
        return $this->render("pages/formulaire.html.twig", [
            'categories'=> $tableauCategories
        ]);
    }

    /**
     * @Route("/formations/formulaire", name="formations.ajouter")
     * @return Response
     */
    public function ajouter(Request $request)
    {
        //dd($request->request->all());
        if ($request->isMethod('POST')) {
            // Récupérez les données du formulaire
            $title = $request->request->get('titre');
            $description = $request->request->get('description');
            $categorieName = $request->request->get('categorie');
            $playlist = $request->request->get('playlist');
            $publishedAt = $request->request->get('date');
            $videoId = $request->request->get('videoId');
        }
        $newFormation = new Formation();
        if($title == null){
            return $this->render("pages/formulaire.html.twig", []);
        }
        $newFormation->setTitle($title);
        $newFormation->setDescription($description);
        $newCategorie = new Categorie();
        $newCategorie->setName($categorieName);
        $newFormation->addCategory($newCategorie);
        $newPlaylist = new Playlist();
        $newPlaylist->setName($playlist);
        $newFormation->setPlaylist($newPlaylist);
        $newFormation->setVideoId($videoId);
        $dateEntree = $newFormation->getPublishedAtString();
        $dateJour = date("d/m/Y");
        if($dateEntree==$dateJour){
            $newFormation->setPublishedAt($publishedAt);
        }

        $this->formationRepository->addFormation($newFormation);
        return $this->render("pages/formation.html.twig", []);
    }


    /**
     * @Route("/formations/formation/modifier/{id}", name="formations.modifier")
     * @param type $id
     * @return Response
     */
    public function modifier($id): void
    {
        // affichage de toutes les données de cette formation avec le même formulaire que formulairehtmltwig 
        // prise en compte des changements: supprimmer l'ancienne formation et ajouterla nouvelle   
        $this->delete($id);
        //$this->ajouter();
    }

}
