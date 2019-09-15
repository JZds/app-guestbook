<?php

namespace App\GuestBookBundle\Repository;

use App\GuestBookBundle\Entity\Comment;
use App\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getTotalCommentsByUser(UserInterface $user = null): int
    {
        return $this->createQueryBuilderForCommentsByUser('comment', $user)
            ->select('COUNT(comment)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getQueryForCommentsByUser(UserInterface $user = null): Query
    {
        $query = $this->createQueryBuilderForCommentsByUser('comment', $user)
            ->addSelect('CASE WHEN comment.approvedAt IS NULL THEN 0 ELSE 1 END AS HIDDEN approvedOrder')
            ->addOrderBy('approvedOrder', 'DESC')
            ->addOrderBy('comment.approvedAt', 'ASC')
            ->getQuery()
        ;
        return $query;
    }

    private function createQueryBuilderForCommentsByUser(string $alias, UserInterface $user = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias);
        $this->updateCommentsQueryByUser($alias, $queryBuilder, $user);
        $queryBuilder->andWhere($alias . '.deleted = false');
        return $queryBuilder;
    }

    private function updateCommentsQueryByUser(
        string $alias,
        QueryBuilder $queryBuilder,
        UserInterface $user = null
    ): QueryBuilder {
        if ($user !== null && !in_array(User::ROLE_ADMIN, $user->getRoles())) {
            $approvedComments = $queryBuilder->expr()->andX(
                $alias . '.approved = true'
            );
            $userUnapprovedComments = $queryBuilder->expr()->andX(
                $alias . '.approved = false',
                $alias . '.user = :user'
            );
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX($approvedComments, $userUnapprovedComments))
                ->setParameter('user', $user)
            ;
        }
        return $queryBuilder;
    }
}
