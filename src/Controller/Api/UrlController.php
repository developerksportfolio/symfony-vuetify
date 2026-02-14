<?php

namespace App\Controller\Api;

use App\Entity\Url;
use App\Repository\UrlRepository;
use App\Service\ShortCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/urls')]
class UrlController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UrlRepository $urlRepository,
        private ShortCodeGenerator $shortCodeGenerator,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = min(100, max(1, $request->query->getInt('limit', 10)));
        $search = $request->query->get('search');

        $result = $this->urlRepository->findPaginated($page, $limit, $search);

        return $this->json([
            'data' => array_map(fn(Url $url) => $url->toArray(), $result['items']),
            'meta' => [
                'total' => $result['total'],
                'page' => $result['page'],
                'limit' => $result['limit'],
                'pages' => (int) ceil($result['total'] / $result['limit']),
            ],
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $url = new Url();
        $url->setOriginalUrl($data['original_url'] ?? '');
        $url->setTitle($data['title'] ?? null);
        $url->setShortCode($this->shortCodeGenerator->generate());

        if (isset($data['is_active'])) {
            $url->setIsActive((bool) $data['is_active']);
        }

        $errors = $this->validator->validate($url);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->persist($url);
        $this->em->flush();

        return $this->json(['data' => $url->toArray()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $url = $this->urlRepository->find($id);

        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $url->toArray()]);
    }

    #[Route('/{id}', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $url = $this->urlRepository->find($id);

        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['original_url'])) {
            $url->setOriginalUrl($data['original_url']);
        }
        if (array_key_exists('title', $data)) {
            $url->setTitle($data['title']);
        }
        if (isset($data['is_active'])) {
            $url->setIsActive((bool) $data['is_active']);
        }

        $errors = $this->validator->validate($url);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->flush();

        return $this->json(['data' => $url->toArray()]);
    }

    #[Route('/{id}', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse
    {
        $url = $this->urlRepository->find($id);

        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($url);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
