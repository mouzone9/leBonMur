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

    #[ORM\Column(type: 'integer')]
    private $authorid;

    static $DRAFT_STATUS = "DRAFT";
    static $PUBLIC_STATUS = "PUBLIC";
    static $SOLD_STATUS = "SOLD";

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: advertisement::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $advertisementId;
    
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(float $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorid;
    }
    
    public function setAuthorId(float $authorid): self
    {
        $this->authorid = $authorid;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

}