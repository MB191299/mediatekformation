<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function PHPUnit\Framework\returnSelf;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * Début de chemin vers les images
     */
    private const cheminImage = "https://i.ytimg.com/vi/";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Range(
     *     min = null,
     *     max = "now",
     *     notInRangeMessage = "La date de publication ne peut pas être postérieure à la date actuelle."
     * )
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $videoId;

    /**
     * @ORM\ManyToOne(targetEntity=Playlist::class, inversedBy="formations", cascade={"persist"}))
     */
    private $playlist;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="formations", cascade={"persist"}))
     */
    private $categories;



    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getPublishedAtString(): string
    {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    public function getPublishedAtCalendar(): string
    {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format("Y-m-d\TH:i");
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMiniature(): ?string
    {
        return self::cheminImage . $this->videoId . "/default.jpg";
    }

    public function getPicture(): ?string
    {
        return self::cheminImage . $this->videoId . "/hqdefault.jpg";
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function appendLinkVideoId(): string
    {
        return "https://youtu.be/".$this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = str_replace("https://youtu.be/", "", $videoId);
        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
