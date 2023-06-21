<?php

namespace App\Controller;

use App\Service\SimulationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimulationController extends AbstractController
{
    #[Route('/', name: 'app_simulation')]
    public function index(): Response
    {
        return $this->render('simulation/index.html.twig');
    }

    #[Route('/simulation/state', name: 'app_simulation_state')]
    public function data(SimulationService $simulationService): JsonResponse
    {
        return $this->json($simulationService->getState());
    }
}
