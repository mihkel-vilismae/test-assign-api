<?php

namespace App\Controller;

use App\Entity\Criteria;
use App\Entity\Filter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class FilterApiController extends AbstractController
{
    #[Route('/filters', name: 'api_filters_get', methods: ['GET'])]
    public function getFilters(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $filters = $entityManager->getRepository(Filter::class)->findAll();
        $jsonData = $serializer->serialize($filters, 'json', ['groups' => ['filter_with_criteria']]);
        $response = new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:3001'); // Or your frontend URL
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization'); // Add other headers as needed
        return $response;
    }

    #[Route('/filters', name: 'api_filters_create', methods: ['POST'])]
    public function createFilter(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $filter = new Filter();
        $filter->setName($data['name']);
        $filter->setSelection($data['selection']);

        if (isset($data['criteria'])) {
            foreach ($data['criteria'] as $criteriaData) {
                $criteria = new Criteria();
                $criteria->setType($criteriaData['type']);
                $criteria->setComparator($criteriaData['comparator']);
                $criteria->setValue($criteriaData['value']);
                $filter->addCriteria($criteria);
            }
        }

        $errors = $validator->validate($filter);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($filter);
        $entityManager->flush();

        $json = $serializer->serialize($filter, 'json', ['groups' => ['filter_with_criteria']]);
        $response = new JsonResponse($json, Response::HTTP_CREATED, [], true);
        return $response;
    }

    #[Route('/filters/{id}', name: 'api_filters_update', methods: ['PUT'])]
    public function updateFilter(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $filter = $entityManager->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return new JsonResponse(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $filter->setName($data['name']);
        $filter->setSelection($data['selection']);

        $filter->getCriteria()->clear();
        if (isset($data['criteria'])) {
            foreach ($data['criteria'] as $criteriaData) {
                $criteria = new Criteria();
                $criteria->setType($criteriaData['type']);
                $criteria->setComparator($criteriaData['comparator']);
                $criteria->setValue($criteriaData['value']);
                $filter->addCriteria($criteria);
            }
        }

        $errors = $validator->validate($filter);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        $json = $serializer->serialize($filter, 'json', ['groups' => ['filter_with_criteria']]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/filters/{id}', name: 'api_filters_delete', methods: ['DELETE'])]
    public function deleteFilter(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $filter = $entityManager->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return new JsonResponse(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($filter);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /*#[Route('/filters/{id}', name: 'api_filter_get_one', methods: ['GET'])] // New route
    public function getFilter(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $filter = $entityManager->getRepository(Filter::class)->find($id);

        if (!$filter) {
            return new JsonResponse(['error' => 'Filter not found'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $serializer->serialize($filter, 'json', ['groups' => ['filter_with_criteria']]);
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }*/


}