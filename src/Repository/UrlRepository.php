<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Url>
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function findByShortCode(string $shortCode): ?Url
    {
        return $this->findOneBy(['shortCode' => $shortCode]);
    }

    public function shortCodeExists(string $shortCode): bool
    {
        return $this->findByShortCode($shortCode) !== null;
    }

    /**
     * @return array{items: Url[], total: int, page: int, limit: int}
     */
    public function findPaginated(int $page = 1, int $limit = 10, ?string $search = null): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC');

        if ($search) {
            $qb->andWhere('u.originalUrl LIKE :search OR u.title LIKE :search OR u.shortCode LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb);
        $total = count($paginator);

        return [
            'items' => iterator_to_array($paginator),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }
}
