<?php

namespace App\Controller;

use App\Repository\UrlRepository;
use App\Service\ClickTracker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    public function __construct(
        private UrlRepository $urlRepository,
        private ClickTracker $clickTracker,
    ) {
    }

    #[Route('/{shortCode}', name: 'app_redirect', requirements: ['shortCode' => '[a-zA-Z0-9]{4,10}'], priority: -100)]
    public function redirectToOriginal(string $shortCode, Request $request): RedirectResponse
    {
        $url = $this->urlRepository->findByShortCode($shortCode);

        if (!$url || !$url->isActive()) {
            throw new NotFoundHttpException('Short URL not found');
        }

        $this->clickTracker->track($url, $request);

        return new RedirectResponse($url->getOriginalUrl(), Response::HTTP_FOUND);
    }
}
