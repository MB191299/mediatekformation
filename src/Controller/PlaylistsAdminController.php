<?php

namespace App\Controller;

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
 * Contrôleur des playlists administratives.
 *
 * @author mb
 */
class PlaylistsAdminController extends AbstractController
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
     * Afficher la liste des playlists
     * @Route("/admin/playlists", name="playlistsadmin")
     * @return Response
     */
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        $nombre = $this->playlistRepository->findAll();
        return $this->render("pages/playlistsadmin.html.twig", [
            'playlists' => $playlists,
            'nombre de formations' => $nombre,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les playlists selon un champ donné
     * 
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="playlistsadmin.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nombreFormations":
                $playlists = $this->ranger($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAll();
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlistsadmin.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    public function ranger($ordre): array
    {
        $cmp = function ($a, $b) {
            return $a->nombreFormations() <=> $b->nombreFormations();
        };

        $a = $this->playlistRepository->findAll();
        usort($a, $cmp);
        if ($ordre == 'ASC') {
            return $a;
        }
        if ($ordre == 'DESC') {
            $b = array_reverse($a);
            return $b;
        }
    }


    /**
     * Recherche les playlists selon un champ donné.
     *
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="playlistsadmin.findallcontain")
     * @param type $champq
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlistsadmin.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche les détails d'une playlist.
     * 
     * @Route("/admin/playlists/playlist/{id}", name="playlistsadmin.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }

    /**
     *  Supprime une playlist.
     * 
     * @Route("/admin/playlists/playlist/supprimer/{id}", name="playlist.delete")
     * @param type $id
     * @return Response
     */
    public function delete($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $nombreFormations = $playlist->nombreFormations();
        if ($nombreFormations == 0) {
            $this->playlistRepository->remove($playlist, true);
            $playlists = $this->playlistRepository->findAll();
            $categories = $this->categorieRepository->findAll();
            return $this->render("pages/playlistsadmin.html.twig", [
                'playlist' => $playlist,
                'playlists' => $playlists,
                'categories' => $categories
            ]);
        } else {
            $message = "Vous ne pouvez pas supprimer cette playlist";
            echo "<script type='text/javascript'>alert('$message');</script>";
            $playlists = $this->playlistRepository->findAll();
            $categories = $this->categorieRepository->findAll();
            return $this->render("pages/playlistsadmin.html.twig", [
                'playlist' => null,
                'playlists' => $playlists,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Affiche le formulaire d'ajout d'une nouvelle playlist.
     * 
     * @Route("/playlists/ajouter", name="formulaireplaylist.affichage")
     * @return Response
     */
    public function afficherFormulairePlaylist(): Response
    {
        $tableauFormations = $this->formationRepository->findAll();
        return $this->render("pages/formulaireplaylist.html.twig", [
            'nom' => null,
            'description' => null,
            'formation' => null,
            'formations' => $tableauFormations,
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une playlist existante.
     * 
     * @Route("/playlists/playlist/modifier/{id}", name="formulaireplaylist.modifier")
     * @param type $id
     * @return Response
     */
    public function afficherFormulairePlaylistRempli($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $formation = $playlist->getFormations()->toArray();
        $tableauFormations = $this->formationRepository->findAll();
        //dd($formation);
        return $this->render("pages/formulaireplaylist.html.twig", [
            'playlist' => $playlist,
            'formation' => $formation,
            'formations' => $tableauFormations,
            'playlist_id' => $id
        ]);
    }

    /**
     * Ajoute une nouvelle playlist à la base de données.
     * 
     * @Route("/playlists/formulaireplaylist", name="playlists.ajouter")
     * @return Response
     */

    public function ajouter(Request $request): Response
    {
        //si id rentré en paramètre ancienne formation supprimmée et nouvelle ajoutée
        $id = $request->request->get('playslist_id');
        //si creation
        // Récupérez les données du formulaire
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        if ($id == null) {
            $newPlaylist = new Playlist();
        } else {
            $newPlaylist = $this->playlistRepository->find($request->request->get('playlist_id'));
        }

        if ($nom == null) {
            return $this->afficherFormulairePlaylist();
        }
        $newPlaylist->setName($nom);
        $newPlaylist->setDescription($description);

        $this->playlistRepository->add($newPlaylist, true);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $newPlaylist,
            'playlistcategories' => null,
            'playlistformations' => null,
        ]);
    }
}
