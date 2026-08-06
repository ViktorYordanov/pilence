<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.projectTags', 'pt')
            ->leftJoin('p.tools', 't')
            ->leftJoin('p.images', 'pi')
            ->addSelect('pt', 't', 'pi')
            ->orderBy('p.year', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBySlug(string $slug): ?Project
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.projectTags', 'pt')
            ->leftJoin('p.tools', 't')
            ->leftJoin('p.images', 'pi')
            ->addSelect('pt', 't', 'pi')
            ->where('p.id = :id OR LOWER(p.title_bg) LIKE LOWER(:slug) OR LOWER(p.title_en) LIKE LOWER(:slug)')
            ->setParameter('slug', '%' . str_replace('-', ' ', $slug) . '%')
            ->setParameter('id', (int)$slug ?: -1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
