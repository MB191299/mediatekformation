<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use ContainerOsEH29E\getFormationsControllerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * Controller to manage categories in the admin panel.
 *
 * @author mb
 */
class CategoriesAdminController extends AbstractController
{

    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;

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


    function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }


    /**
     * Display all categories.
     *
     * @Route("/admin/categories", name="categoriesadmin")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/categoriesadmin.html.twig", [
            'categories' => $categories
        ]);
    }

    /**
     * Delete a category.
     *
     * @Route("/admin/categories/supprimer/{id}", name="categorie.delete")
     * @param int $id The ID of the category to delete
     * @return Response
     */
    public function delete($id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        $nombreFormations = $categorie->nombreFormations();
        if ($nombreFormations == 0) {
            $this->categorieRepository->remove($categorie, true);
            $this->get('session')->set('message', 'La catégorie a bien été supprimée.');
            return $this->redirectToRoute('categoriesadmin');
        } else {
            $this->get('session')->set('message', 'Vous ne pouvez pas supprimer cette catégorie.');
            return $this->redirectToRoute('categoriesadmin');
        }
    }

    // Add a new category to the database.
    // Do the same for deletion.
    /**
     * Add a new category.
     *
     * @Route("/categories/formulairecategorie", name="categories.ajouter")
     * @param Request $request The request object
     * @return Response
     */
    public function ajouter(Request $request): Response
    {
        $nom = $request->request->get('nom');

        // Vérifier si le nom de la catégorie existe déjà
        $categorieExistante = $this->categorieRepository->findOneBy(['name' => $nom]);

        if ($categorieExistante) {
            // Si la catégorie existe déjà, afficher un message d'erreur
            $this->get('session')->set('message', 'Attention: ce nom de catégorie existe déjà.');
            return $this->redirectToRoute('categoriesadmin');
        } else {
            $newCategorie = new Categorie();
            $newCategorie->setName($nom);
            $this->categorieRepository->add($newCategorie, true);
            return $this->redirectToRoute('categoriesadmin');
        }
    }
}
