<?php

namespace App\Service;

use App\Repository\ClickRepository;
use App\Repository\UrlRepository;

class AnalyticsService
{
    public function __construct(
        private ClickRepository $clickRepository,
        private UrlRepository $urlRepository,
    ) {
    }

    public function getFullAnalytics(int $urlId, string $from, string $to): array
    {
        return [
            'clicks_over_time' => $this->clickRepository->getClicksOverTime($urlId, $from, $to),
            'top_referrers' => $this->clickRepository->getTopReferrers($urlId),
            'browsers' => $this->clickRepository->getBrowserBreakdown($urlId),
            'os' => $this->clickRepository->getOsBreakdown($urlId),
            'devices' => $this->clickRepository->getDeviceBreakdown($urlId),
        ];
    }

    public function getClicksOverTime(int $urlId, string $from, string $to): array
    {
        return $this->clickRepository->getClicksOverTime($urlId, $from, $to);
    }

    public function getTopReferrers(int $urlId): array
    {
        return $this->clickRepository->getTopReferrers($urlId);
    }

    public function getBrowserBreakdown(int $urlId): array
    {
        return $this->clickRepository->getBrowserBreakdown($urlId);
    }

    public function getDashboardStats(): array
    {
        $totalUrls = $this->urlRepository->count([]);
        $activeUrls = $this->urlRepository->count(['isActive' => true]);
        $totalClicks = $this->clickRepository->getTotalClicks();
        $todayClicks = $this->clickRepository->getTodayClicks();

        $now = new \DateTime();
        $from = (clone $now)->modify('-30 days')->format('Y-m-d 00:00:00');
        $to = $now->format('Y-m-d 23:59:59');

        return [
            'total_urls' => $totalUrls,
            'active_urls' => $activeUrls,
            'total_clicks' => $totalClicks,
            'today_clicks' => $todayClicks,
            'clicks_over_time' => $this->clickRepository->getGlobalClicksOverTime($from, $to),
        ];
    }
}
