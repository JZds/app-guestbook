<?php

namespace App\GuestBookBundle\Service;

use App\GuestBookBundle\Entity\Comment;
use App\GuestBookBundle\Repository\CommentRepository;
use App\UserBundle\Entity\User;
use App\UserBundle\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentManager
{
    private $paginator;
    private $commentRepository;
    private $validator;
    private $entityManager;

    public function __construct(
        PaginatorInterface $paginator,
        CommentRepository $commentRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        $this->paginator = $paginator;
        $this->commentRepository = $commentRepository;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $page
     * @param int $limit
     * @param User|null $user
     * @return PaginationInterface
     */
    public function getPaginatedComments(int $page, int $limit, User $user = null)
    {
        return $this->paginator->paginate($this->commentRepository->getQueryForCommentsByUser($user), $page, $limit);
    }

    public function createComment(UserInterface $user, array $commentData): Comment
    {
        $comment = (new Comment())
            ->setContent($commentData['content'])
            ->setUser($user)
        ;
        $violations = $this->validator->validate($comment);
        if ($violations->count() > 0) {
            $errors = [];
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            throw new ValidationException('invalid_validation', $errors, 400);
        }
        $this->entityManager->persist($comment);

        return $comment;
    }
}
