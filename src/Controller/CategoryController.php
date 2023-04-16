<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/category')]
class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $repo)
    {
    }

    #[Route('/{create}',methods: 'POST')]
    public function create(Request $request)
    {
        $category = $this->createCategory('Nouvelle catégorie');
    }

    #[Route(methods: 'GET')]
    public function all(): Response
    {
        return $this->json($this->repo->findAll());
    }

    #[Route('/{id}', methods: 'GET')]
    public function one(Category $category)
    {

        return $this->json($category);
    }
    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer)
    {
        try {
            $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
            $this->repo->save($category, true);

            return $this->json($category, Response::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('Invalid json', Response::HTTP_BAD_REQUEST);
        }


    }


    #[Route("/{id}", methods: 'DELETE')]
    public function delete(Category $category)
    {
        $this->repo->remove($category, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }


    #[Route("/{id}", methods: ['PATCH', 'PUT'])]
    public function patch(Category $category, Request $request, SerializerInterface $serializer)
    {
        try {

            $serializer->deserialize($request->getContent(), Category::class, 'json', [
                'object_to_populate' => $category
            ]);
            $this->repo->save($category, true);
            return $this->json($category);

        } catch (ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('Invalid json', Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route("/{id}/items", methods: 'GET')]
    public function getProducts(Category $category)
    {
        return $this->json($category->getItems());
    }

    public function createCategory($name)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $category = new Category();
        $category->setName($name);
        
        $entityManager->persist($category);
        $entityManager->flush();
        
        return $category;
    }
    
    
}