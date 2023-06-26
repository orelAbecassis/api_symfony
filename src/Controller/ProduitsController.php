<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
//use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;


class ProduitsController extends AbstractController
{
    #[Route('/api/produits', name: 'app_produits', methods: ['GET'])]
    public function getProduitsList(ProduitsRepository $produitsRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);
        $idCache = "getProduitsList-" .$page. "-" .$limit;

        $produitList = $cachePool->get($idCache, function (ItemInterface $item)
        use($produitsRepository, $page, $limit){
            echo "lelement nest pas encore en cache !";
            $item->tag("produitCache");
            return  $produitsRepository->findAllWithPagination($page,$limit);

        });

        $context = SerializationContext::create()->setGroups(['getProduits']);
        $jsonPorduitsList = $serializer->serialize($produitList, 'json' ,$context);

        return new JsonResponse(
            $jsonPorduitsList, Response::HTTP_OK,[],true
        );
    }

    #[Route('/api/produits/{id}', name: 'unProduit', methods:['GET'])]
    public function getDetailProduit(Produits $produits, SerializerInterface $serializer)
    {
        $context = SerializationContext::create()->setGroups(['getProduits']);
        $jsonPorduit = $serializer->serialize($produits, 'json' ,$context);

        return new JsonResponse($jsonPorduit, Response::HTTP_OK, [], true);
    }

    #[Route('/api/produits/{id}', name: 'supProduit', methods:['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un produit')]
    public function deleteProduit(Produits $produits, EntityManagerInterface $em, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(['produitsCache']);
        $em->remove($produits);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/produits', name:"createProduit", methods:['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un produit')]
    public function createProduit(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, CategorieRepository $categorieRepository, ValidatorInterface $validator): JsonResponse
    {
        $produits = $serializer->deserialize($request->getContent(), Produits::class, 'json');

        #on verifie les erreurs
        $errors = $validator->validate($produits);

        if($errors->count()>0)
        {
            return new JsonResponse($serializer->serialize($errors, 'json'),
            JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $content = $request->toArray();
        $idCateg = $content['id_categ'] ?? -1;
        $produits->setIdCateg($categorieRepository->find($idCateg));


        $em->persist($produits);
        $em->flush();

        $context = SerializationContext::create()->setGroups(['getProduits']);
        $jsonProduit = $serializer->serialize($produits, 'json', $context);
        $location = $urlGenerator->generate('unProduit', ['id' =>$produits->getId(), UrlGeneratorInterface::ABSOLUTE_URL]);

        return new JsonResponse($jsonProduit, Response::HTTP_CREATED, ["location" =>$location], true);
    }



    #[Route('/api/produits/{id}', name:'updateProduit', methods:['PUT'])]
    public function udpateProduit(Request $request, SerializerInterface $serializer, Produits $currentProduit, EntityManagerInterface $em, CategorieRepository $categorieRepository, TagAwareCacheInterface $cache, ValidatorInterface $validator): JsonResponse
    {
        $updateProduit = $serializer->deserialize($request->getContent(),Produits::class, 'json');

        $currentProduit->setNom($updateProduit->getNom());
        $currentProduit->setImage($updateProduit->getImage());
        $currentProduit->setDescription($updateProduit->getDescription());
        $currentProduit->setPrix($updateProduit->getPrix());

        $error = $validator->validate($currentProduit);
        if($error->count()>0)
        {
            return new JsonResponse($serializer->serialize($error,'json'),
            JsonResponse::HTTP_BAD_REQUEST,[], true);
        }

        $content = $request->toArray();
        $idCateg = $content['id_categ'] ?? -1;
        $updateProduit->setIdCateg($categorieRepository->find($idCateg));

        $em->persist($updateProduit);
        $em->flush();

        $cache->invalidateTags("produitCache");
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
