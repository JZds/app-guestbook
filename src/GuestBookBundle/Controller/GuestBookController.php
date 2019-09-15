<?php

namespace App\GuestBookBundle\Controller;

use App\GuestBookBundle\Repository\CommentRepository;
use App\GuestBookBundle\Service\CommentManager;
use App\UserBundle\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use App\GuestBookBundle\Entity\Comment;
use LogicException;

class GuestBookController extends AbstractController
{
    const PAGE_LIMIT = 20;

    const COMMENT_PUT_APPROVE = 'approve';
    const COMMENT_PUT_DISAPPROVE = 'disapprove';
    const COMMENT_PUT_DELETE = 'delete';

    private $commentManager;
    private $commentRepository;
    private $entityManager;

    public function __construct(
        CommentManager $commentManager,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->commentManager = $commentManager;
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
    }

    public function renderGuestBook(Request $request): Response
    {
        $paginator = $this->commentManager->getPaginatedComments(
            $request->query->getInt(
                'page',
                ceil($this->commentRepository->getTotalCommentsByUser($this->getUser()) / self::PAGE_LIMIT)
            ),
            self::PAGE_LIMIT,
            $this->getUser()
        );

        return $this->render(
            '@GuestBook/guestbook.html.twig',
            ['user' => $this->getUser(), 'pagination' => $paginator]
        );
    }

    public function postComment(Request $request): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        /** @var UserInterface $user */
        $user = $this->getUser();
        try {
            $this->commentManager->createComment($user, $request->request->all());
        } catch (ValidationException $validationException) {
            foreach ($validationException->getData() as $error) {
                $this->addFlash('warning', $error);
            }
        }

        $this->entityManager->flush();
        return $this->redirectToRoute('app_guest_book.render.guest_book');
    }

    public function approveComment(int $commentId): Response
    {
        return $this->initiateCommentPutAction(self::COMMENT_PUT_APPROVE, $commentId);
    }

    public function disapproveComment(int $commentId): Response
    {
        return $this->initiateCommentPutAction(self::COMMENT_PUT_DISAPPROVE, $commentId);
    }

    public function deleteComment(int $commentId): Response
    {
        return $this->initiateCommentPutAction(self::COMMENT_PUT_DELETE, $commentId);
    }

    private function initiateCommentPutAction(string $action, int $commentId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        /** @var Comment $comment */
        $comment = $this->commentRepository->find($commentId);
        if ($comment === null) {
            return new Response('Comment not found', 404);
        }

        if ($action === self::COMMENT_PUT_APPROVE) {
            $comment->approve();
        } elseif ($action === self::COMMENT_PUT_DISAPPROVE) {
            $comment->disapprove();
        } elseif ($action === self::COMMENT_PUT_DELETE) {
            $comment->delete();
        } else {
            throw new LogicException('Invalid action provided');
        }

        $this->entityManager->flush();
        return new Response('', 204);
    }
}