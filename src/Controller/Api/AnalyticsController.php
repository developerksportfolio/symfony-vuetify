<?php

namespace App\Controller\Api;

use App\Repository\UrlRepository;
use App\Service\AnalyticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnalyticsController extends AbstractController
{
    public function __construct(
        private AnalyticsService $analyticsService,
        private UrlRepository $urlRepository,
    ) {
    }

    #[Route('/api/urls/{id}/analytics', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function fullAnalytics(int $id, Request $request): JsonResponse
    {
        $url = $this->urlRepository->find($id);
        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        $from = $request->query->get('from', (new \DateTime('-30 days'))->format('Y-m-d 00:00:00'));
        $to = $request->query->get('to', (new \DateTime())->format('Y-m-d 23:59:59'));

        return $this->json([
            'data' => $this->analyticsService->getFullAnalytics($id, $from, $to),
        ]);
    }

    #[Route('/api/urls/{id}/analytics/clicks', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function clicksOverTime(int $id, Request $request): JsonResponse
    {
        $url = $this->urlRepository->find($id);
        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        $from = $request->query->get('from', (new \DateTime('-30 days'))->format('Y-m-d 00:00:00'));
        $to = $request->query->get('to', (new \DateTime())->format('Y-m-d 23:59:59'));

        return $this->json([
            'data' => $this->analyticsService->getClicksOverTime($id, $from, $to),
        ]);
    }

    #[Route('/api/urls/{id}/analytics/referrers', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function topReferrers(int $id): JsonResponse
    {
        $url = $this->urlRepository->find($id);
        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $this->analyticsService->getTopReferrers($id),
        ]);
    }

    #[Route('/api/urls/{id}/analytics/browsers', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function browserBreakdown(int $id): JsonResponse
    {
        $url = $this->urlRepository->find($id);
        if (!$url) {
            return $this->json(['error' => 'URL not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $this->analyticsService->getBrowserBreakdown($id),
        ]);
    }

    #[Route('/api/analytics/dashboard', methods: ['GET'])]
    public function dashboard(): JsonResponse
    {
        return $this->json([
            'data' => $this->analyticsService->getDashboardStats(),
        ]);
    }
}
