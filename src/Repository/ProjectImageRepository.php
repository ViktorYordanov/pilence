<?php

namespace App\Repository;

use App\Entity\ProjectImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectImage>
 */
class ProjectImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectImage::class);
    }

    public function findByProjectId(int $projectId): array
    {
        return $this->createQueryBuilder('pi')
            ->where('pi.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->orderBy('pi.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findVisibleByProjectId(int $projectId): array
    {
        return $this->createQueryBuilder('pi')
            ->where('pi.project = :projectId')
            ->andWhere('pi.isVisible = true')
            ->setParameter('projectId', $projectId)
            ->orderBy('pi.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
