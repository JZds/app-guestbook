<?php

namespace App\GuestBookBundle\Repository;

use App\GuestBookBundle\Entity\Comment;
use App\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getQueryForCommentsByUser(User $user = null)
    {
        $queryBuilder = $this->createQueryBuilder('comment');

//        $this->updateCommentsQueryByUser($queryBuilder);

        return $queryBuilder
//            ->andWhere('comment.approved = true')
//            ->andWhere('comment.deleted = false')
            ->orderBy('comment.createdAt', 'ASC')
            ->getQuery()
        ;
    }

    private function updateCommentsQueryByUser(QueryBuilder $queryBuilder, User $user = null)
    {
        // xor other users
        if ($user !== null) {
            $queryBuilder
                ->orWhere('comment.user = :user')
                ->setParameter('user', $user)
            ;
        } else {
            $queryBuilder->andWhere('comment.approved = true');
        }
        return $queryBuilder;
    }
}
