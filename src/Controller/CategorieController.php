<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Cache\TagAwareCacheInterface;


class CategorieController extends AbstractController
{
    #[Route('/api/categories/{id}', name: 'categorie',methods: ['GET'])]
    public function getCateg(Categorie $categorie, SerializerInterface $serializer): JsonResponse
    {

       $jsonCategList = $serializer->serialize($categorie,'json', [
           'groups'=>'getProduits'
       ]);
       return new JsonResponse($jsonCategList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories/', name:'unCateg', methods:['GET'])]
    public function getAllCateg(CategorieRepository $categorieRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cachePool):JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit',3);

        $categList =$cachePool;
        $jsonCategList = $serializer->serialize($categList, 'json',
        ['groups'=>'getProduits' ]);
        return new JsonResponse($jsonCategList, Response::HTTP_OK, [], true);

    }


    #[ROUTE('/api/categories/{id}', name: 'supCategorie', methods:['DELETE'])]
    public function deleteCateg(Categorie $categorie, EntityManagerInterface $em)
    {
        $em->remove($categorie);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[ROUTE('/api/categories', name:'createCategorie', methods:['POST'])]
    public function createCateg(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $categorie = $serializer->deserialize($request->getContent(), Categorie::class, 'json');
        $em->persist($categorie);
        $em->flush();

        $jsonCateg = $serializer->serialize($categorie, 'json', [
            'groups'=>'getProduits']);
        return new JsonResponse($jsonCateg, Response::HTTP_CREATED, [], true);

    }

    #[ROUTE('api/categories/{id}', name:'updateCategorie', methods:['PUT'])]
    public function updateCateg(Request $request, SerializerInterface $serializer, Categorie $currentCategorie, EntityManagerInterface $em, ProduitsRepository $produitsRepository): JsonResponse
    {
        $updateCateg = $serializer->deserialize($request->getContent(), Categorie::class, 'json',
        [AbstractNormalizer::OBJECT_TO_POPULATE =>$currentCategorie]);
        //$content = $request->toArray();
        //$Categorie = $content['id_categ'] ?? -1;
        //$updateCateg->setProduits($produitsRepository->find($idCateg));

        $em->persist($updateCateg);
        $em->flush();


        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
