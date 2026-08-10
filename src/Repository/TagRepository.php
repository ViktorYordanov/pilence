<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findBySlug(string $slug): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.nameBg', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
