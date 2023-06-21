<?php

namespace App\Controller;

use App\Entity\Zombie;
use App\Form\ZombieType;
use App\Repository\ZombieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZombieController extends AbstractController
{
    public function __construct(private readonly ZombieRepository $zombieRepository)
    {
    }

    #[Route('/zombie/all', name: 'app_index_zombie')]
    public function index(): Response
    {
        $zombies = $this->zombieRepository->findBy([], ['id' => 'ASC']);

        return $this->render('zombie/index.html.twig', ['zombies' => $zombies]);
    }
    #[Route('/zombie/new', name: 'app_new_zombie')]
    public function new(Request $request): Response
    {
        $zombie = new Zombie();

        $zombie->setX(1)
            ->setY(1);

        $form = $this->createForm(ZombieType::class, $zombie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zombie = $form->getData();

            $this->zombieRepository->save($zombie, true);

            $this->addFlash('notice', 'A new zombie has been successfully created!');

            return $this->redirectToRoute('app_simulation');
        }

        return $this->render('zombie/new.html.twig', ['form' => $form]);
    }
}
