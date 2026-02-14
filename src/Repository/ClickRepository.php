<?php

namespace App\Repository;

use App\Entity\Click;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Click>
 */
class ClickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Click::class);
    }

    /**
     * @return array<array{date: string, clicks: int}>
     */
    public function getClicksOverTime(int $urlId, string $from, string $to): array
    {
        return $this->createQueryBuilder('c')
            ->select("DATE(c.clickedAt) AS date, COUNT(c.id) AS clicks")
            ->where('c.url = :urlId')
            ->andWhere('c.clickedAt >= :from')
            ->andWhere('c.clickedAt <= :to')
            ->setParameter('urlId', $urlId)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array<array{referrer: string, clicks: int}>
     */
    public function getTopReferrers(int $urlId, int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->select("COALESCE(c.referrer, 'Direct') AS referrer, COUNT(c.id) AS clicks")
            ->where('c.url = :urlId')
            ->setParameter('urlId', $urlId)
            ->groupBy('referrer')
            ->orderBy('clicks', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array<array{browser: string, clicks: int}>
     */
    public function getBrowserBreakdown(int $urlId): array
    {
        return $this->createQueryBuilder('c')
            ->select("COALESCE(c.browser, 'Unknown') AS browser, COUNT(c.id) AS clicks")
            ->where('c.url = :urlId')
            ->setParameter('urlId', $urlId)
            ->groupBy('browser')
            ->orderBy('clicks', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array<array{os: string, clicks: int}>
     */
    public function getOsBreakdown(int $urlId): array
    {
        return $this->createQueryBuilder('c')
            ->select("COALESCE(c.os, 'Unknown') AS os, COUNT(c.id) AS clicks")
            ->where('c.url = :urlId')
            ->setParameter('urlId', $urlId)
            ->groupBy('os')
            ->orderBy('clicks', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array<array{device_type: string, clicks: int}>
     */
    public function getDeviceBreakdown(int $urlId): array
    {
        return $this->createQueryBuilder('c')
            ->select("COALESCE(c.deviceType, 'Unknown') AS device_type, COUNT(c.id) AS clicks")
            ->where('c.url = :urlId')
            ->setParameter('urlId', $urlId)
            ->groupBy('device_type')
            ->orderBy('clicks', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @return array<array{date: string, clicks: int}>
     */
    public function getGlobalClicksOverTime(string $from, string $to): array
    {
        return $this->createQueryBuilder('c')
            ->select("DATE(c.clickedAt) AS date, COUNT(c.id) AS clicks")
            ->where('c.clickedAt >= :from')
            ->andWhere('c.clickedAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    public function getTotalClicks(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTodayClicks(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('DATE(c.clickedAt) = :today')
            ->setParameter('today', date('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
