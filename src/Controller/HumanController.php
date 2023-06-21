<?php

namespace App\Controller;

use App\Entity\Human;
use App\Form\HumanType;
use App\Repository\HumanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HumanController extends AbstractController
{
    public function __construct(private readonly HumanRepository $humanRepository)
    {
    }

    #[Route('/human/all', name: 'app_index_human')]
    public function index(): Response
    {
        $humans = $this->humanRepository->findBy([], ['id' => 'ASC']);

        return $this->render('human/index.html.twig', ['humans' => $humans]);
    }

    #[Route('/human/new', name: 'app_new_human')]
    public function new(Request $request): Response
    {
        $human = new Human();

        $human->setX(1)
            ->setY(1)
            ->setAmmoCount(0)
            ->setFoodCount(0);

        $form = $this->createForm(HumanType::class, $human);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $human = $form->getData();

            $this->humanRepository->save($human, true);

            $this->addFlash('notice', 'A new human has been successfully created!');

            return $this->redirectToRoute('app_simulation');
        }

        return $this->render('human/new.html.twig', ['form' => $form]);
    }
}
