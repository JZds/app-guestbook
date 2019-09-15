<?php

namespace App\GuestBookBundle\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $username;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var bool
     */
    private $approved;

    /**
     * @var DateTimeInterface|null
     */
    private $approvedAt;

    /**
     * @var bool
     */
    private $deleted;

    /**
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @var DateTimeInterface|null
     */
    private $deletedAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->approved = false;
        $this->deleted = false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return $this
     */
    public function disapprove()
    {
        $this->approved = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function approve()
    {
        $this->approved = true;
        if ($this->approvedAt === null) {
            $this->setApprovedAt(new DateTimeImmutable());
        }

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * @param DateTimeInterface|null $approvedAt
     * @return $this
     */
    public function setApprovedAt(DateTimeInterface $approvedAt)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     *
     * @return $this
     */
    public function setDeleted(bool $deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->deleted = true;
        if ($this->deletedAt === null) {
            $this->setDeletedAt(new DateTimeImmutable());
        }

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTimeInterface $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(DateTimeInterface $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}