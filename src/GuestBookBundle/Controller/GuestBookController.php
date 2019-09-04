<?php

namespace App\GuestBookBundle\Controller;

use App\GuestBookBundle\Service\CommentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestBookController extends AbstractController
{
    private $commentManager;
    private $entityManager;

    public function __construct(CommentManager $commentManager, EntityManagerInterface $entityManager)
    {
        $this->commentManager = $commentManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function renderGuestBook(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_user.login');
        }

        $paginator = $this->commentManager->getPaginatedComments(
            $request->query->getInt('page', 1),
            20,
            $this->getUser()
        );

        return $this->render(
            '@GuestBook/guestbook.html.twig',
            ['user' => $this->getUser(), 'pagination' => $paginator]
        );
    }

    public function postComment(Request $request)
    {
        $this->commentManager->createComment($this->getUser(), $request->request->all());
        $this->entityManager->flush();
        return $this->redirectToRoute('app_guest_book.render.guest_book');
    }
}