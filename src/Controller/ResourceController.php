<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Form\ResourceType;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    public function __construct(private readonly ResourceRepository $resourceRepository)
    {
    }

    #[Route('/resource/all', name: 'app_index_resource')]
    public function index(): Response
    {
        $resources = $this->resourceRepository->findBy([], ['id' => 'ASC']);

        return $this->render('resource/index.html.twig', ['resources' => $resources]);
    }

    #[Route('/resource/new', name: 'app_new_resource')]
    public function new(Request $request): Response
    {
        $resource = new Resource();

        $resource->setX(1)
            ->setY(1)
            ->setAmount(1);

        $form = $this->createForm(ResourceType::class, $resource);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resource = $form->getData();

            $this->resourceRepository->save($resource, true);

            $this->addFlash('notice', 'A new resource has been successfully created!');

            return $this->redirectToRoute('app_simulation');
        }

        return $this->render('resource/new.html.twig', ['form' => $form]);
    }
}
