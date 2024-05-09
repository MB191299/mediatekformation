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

/**
 * Contrôleur des playlists
 *
 * @author emds
 */
class PlaylistsController extends AbstractController
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
     * Affiche la liste des playlists.
     * 
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        $nombre = $this->playlistRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'nombre de formations' => $nombre,
            'categories' => $categories
        ]);
    }

    /**
     *  Trie et affiche la liste des playlists en fonction d'un champ et d'un ordre donnés.
     * 
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
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
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les playlists en fonction du nombre de formations.
     *
     * @param string $ordre
     * @return array
     */
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
     * Recherche et affiche les playlists en fonction d'un champ et d'une valeur donnés.
     *
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche les détails d'une playlist spécifique.
     *
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     * @param int $id
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
}
