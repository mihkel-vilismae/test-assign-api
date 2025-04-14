<?php

namespace App\Controller;

use App\Entity\Filter;
use App\Entity\Criterion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/filters')]
class FilterApiController extends AbstractController
{
    #[Route('', name: 'api_filters_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $filters = $entityManager->getRepository(Filter::class)->findAll();

        $serializedFilters = $serializer->serialize($filters, 'json', ['groups' => ['filter_read']]);

        $response = new JsonResponse($serializedFilters, Response::HTTP_OK, [], true);
        $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:3000'); // Or your frontend URL
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization'); // Add other headers as needed
        return $response;
    }

    #[Route('/create', name: 'api_filters_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $filter = new Filter();
            $filter->setName($data['name']);
            $filter->setSelection($data['selection'] ?? null);

            foreach ($data['criteria'] as $criterionData) {
                $criterion = new Criterion();
                $criterion->setType($criterionData['type']);
                $criterion->setComparator($criterionData['comparator']);
                $criterion->setValue($criterionData['value']);
                $filter->addCriterion($criterion);
            }

            $entityManager->persist($filter);
            $entityManager->flush();

            $serializedFilter = $serializer->serialize($filter, 'json', ['groups' => ['filter_read']]);

            return new JsonResponse($serializedFilter, Response::HTTP_CREATED, [], true);

        } catch (\JsonException $e) {
            return new JsonResponse(['error' => 'Invalid JSON format: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'api_filters_show', methods: ['GET'])]
    public function show(Filter $filter, SerializerInterface $serializer): JsonResponse
    {
        $serializedFilter = $serializer->serialize($filter, 'json', ['groups' => ['filter_read']]);
        return new JsonResponse($serializedFilter, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/edit', name: 'api_filters_edit', methods: ['PUT'])]
    public function edit(Request $request, Filter $filter, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            $filter->setName($data['name']);
            $filter->setSelection($data['selection'] ?? null);

            foreach ($filter->getCriteria() as $existingCriterion) {
                $filter->removeCriterion($existingCriterion);
                $entityManager->remove($existingCriterion);
            }

            foreach ($data['criteria'] as $criterionData) {
                $criterion = new Criterion();
                $criterion->setType($criterionData['type']);
                $criterion->setComparator($criterionData['comparator']);
                $criterion->setValue($criterionData['value']);
                $filter->addCriterion($criterion);
            }

            $entityManager->flush();

            $serializedFilter = $serializer->serialize($filter, 'json', ['groups' => ['filter_read']]);
            return new JsonResponse($serializedFilter, Response::HTTP_OK, [], true);

        } catch (\JsonException $e) {
            return new JsonResponse(['error' => 'Invalid JSON format: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'api_filters_delete', methods: ['DELETE'])]
    public function delete(Filter $filter, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($filter);
        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}