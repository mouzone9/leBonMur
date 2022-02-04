<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $text;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    static $DRAFT_STATUS = "DRAFT";
    static $PUBLIC_STATUS = "PUBLIC";
    static $SOLD_STATUS = "SOLD";

    #[ORM\ManyToOne(targetEntity: advertisement::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $advertisementId;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $authorId;

    #[ORM\OneToMany(mappedBy: 'commentsId', targetEntity: Answers::class, orphanRemoval: true)]
    private $answers;
    
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdvertisementId(): ?advertisement
    {
        return $this->advertisementId;
    }

    public function setAdvertisementId(?advertisement $advertisementId): self
    {
        $this->advertisementId = $advertisementId;

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

    /**
     * @return Collection|Answers[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setCommentsId($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getCommentsId() === $this) {
                $answer->setCommentsId(null);
            }
        }

        return $this;
    }

}