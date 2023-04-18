<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use App\Service\Uploader;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[Route('/api/picture')]
class PictureController extends AbstractController
{

    public function __construct(private PictureRepository $repo)
    {
    }

    #[Route(methods: 'GET')]
    public function getAll(): JsonResponse
    {
        return $this->json($this->repo->findAll());
    }
    #[Route('/{id}', methods: 'GET')]
    public function getOne(Picture $picture): JsonResponse
    {
        return $this->json($picture);
    }
    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer, Uploader $uploader)
    {
        try {

            $picture = $serializer->deserialize($request->getContent(), Picture::class, 'json');

            $picture->setSrc($uploader->upload($picture->getSrc()));


            $this->repo->save($picture, true);
            return $this->json($picture, Response::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('invalid json', Response::HTTP_BAD_REQUEST);

        }

    }
    #[Route('/{id}', methods: 'PATCH')]
    public function update(Picture $picture, Request $request, SerializerInterface $serializer)
    {
        try {

            $picture = $serializer->deserialize($request->getContent(), Picture::class, 'json', [
                'object_to_populate' => $picture
            ]);

            if ($this->getUser() != $picture->getItems()) {
                return $this->json(['error' => 'Access denied'], Response::HTTP_UNAUTHORIZED);
            }

            $this->repo->save($picture, true);
            return $this->json($picture);
        } catch (ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('invalid json', Response::HTTP_BAD_REQUEST);

        }

    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(Picture $picture): JsonResponse
    {

        if ($this->getUser() != $picture->getItems()) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_UNAUTHORIZED);
        }
        $this->repo->remove($picture);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}