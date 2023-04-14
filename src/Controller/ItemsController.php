<?php

namespace App\Controller;

use App\Entity\Items;
use App\Repository\ItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[Route('/api/items')]
/**
 * Summary of ItemsController
 */
class ItemsController extends AbstractController
{
    public function __construct(private ItemsRepository $repo){
    }

    #[Route(methods: 'GET')]
    /**
     * Function qui nous permet de récuperer tous les élément
     * @return Response
     */
    public function all(): Response{
        return $this->json($this->repo->findAll());
    }

    #[Route('/{id}', methods: 'GET')]
    /**
     * function qui nous permet de récuperer un élement spécifique avec son id
     * @param Items $item
     * @return Response
     */
    public function one(Items $item): Response{
        return $this->json($item);
    }

    #[Route(methods:'POST')]
    /**
     * La fonction add utilise le service SerializerInterface pour désérialiser le contenu de la demande (request) 
     * en un objet de la classe Item en utilisant le format JSON. Ensuite, elle appelle la méthode "save" du repository 
     * (un objet qui assure la persistance des données) pour enregistrer l'objet Item nouvellement créé.
     * Si la validation de l'objet échoue, elle renvoie les violations de validation sous forme de réponse HTTP avec un code d'erreur 400 (mauvaise requête). 
     * Si la désérialisation échoue en raison d'une donnée invalide, elle renvoie une réponse HTTP avec le message "Invalid json" et un code d'erreur 400.
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializer){
        try{
            $items = $serializer->deserialize($request->getContent(), Items::class, 'json');
            $this->repo->save($items, true);
            return $this->json($items, Response::HTTP_CREATED);

        }catch(ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('Invalid json', Response::HTTP_BAD_REQUEST);
        }

    }

    #[Route("/{id}", methods: 'DELETE')]
    public function delete(Items $item)
    {
        $this->repo->remove($item, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route("/{id}", methods: ['PATCH', 'PUT'])]
    public function patch(Items $item, Request $request, SerializerInterface $serializer)
    {
        try {

            $serializer->deserialize($request->getContent(), Product::class, 'json', [
                'object_to_populate' => $item
            ]);
            $this->repo->save($item, true);
            return $this->json($item);

        } catch (ValidationFailedException $e) {
            return $this->json($e->getViolations(), Response::HTTP_BAD_REQUEST);
        } catch (NotEncodableValueException $e) {
            return $this->json('Invalid json', Response::HTTP_BAD_REQUEST);
        }

    }

}
