<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EditorRepository")
 */
class Editor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You must enter an business name.")
     * @Assert\Length(min="2", minMessage="Business name must be at least {{ limit }} characters long")
     */
    private $buisnessName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You must enter an nationality.")
     * @Assert\Length(min="2", minMessage="Nationality must be at least {{ limit }} characters long.")
     */
    private $nationality;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="editor", cascade={"detach", "persist"})
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuisnessName(): ?string
    {
        return $this->buisnessName;
    }

    public function setBuisnessName(string $buisnessName): self
    {
        $this->buisnessName = $buisnessName;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setEditor($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getEditor() === $this) {
                $game->setEditor(null);
            }
        }

        return $this;
    }
}
