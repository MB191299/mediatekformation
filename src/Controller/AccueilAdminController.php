<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur de l'accueil en admins
 *
 * @author emds
 */
class AccueilAdminController extends AbstractController
{

    /**
     * @var FormationRepository
     */
    private $repository;

    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/accueil", name="accueiladmin")
     * @return Response
     */
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueiladmin.html.twig", [
            'formations' => $formations
        ]);
    }

    /**
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    public function cgu(): Response
    {
        return $this->render("pages/cgu.html.twig");
    }
}
