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
    #[Route('/zombie/new', name: 'app_new_zombie')]
    public function new(Request $request, ZombieRepository $zombieRepository): Response
    {
        $zombie = new Zombie();

        $zombie->setX(1)
            ->setY(1);

        $form = $this->createForm(ZombieType::class, $zombie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zombie = $form->getData();

            $zombieRepository->save($zombie, true);

            $this->addFlash('notice', 'A new zombie has been successfully created!');

            return $this->redirectToRoute('app_simulation');
        }

        return $this->render('zombie/new.html.twig', ['form' => $form]);
    }
}
