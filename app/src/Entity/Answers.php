<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\Column(type: 'datetime')]
    private $createAt;

    #[ORM\ManyToOne(targetEntity: comments::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private $commentsId;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'answers')]
    private $authorId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCommentsId(): ?comments
    {
        return $this->commentsId;
    }

    public function setCommentsId(?comments $commentsId): self
    {
        $this->commentsId = $commentsId;

        return $this;
    }

    public function getAuthorId(): ?user
    {
        return $this->authorId;
    }

    public function setAuthorId(?user $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }
}
