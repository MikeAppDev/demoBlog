<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * ORM = Objet relationnel manager.
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255) // on a une valeur nullable true que l'on peut retirer si on a coché yes.
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="tags")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection(); // ca c'est juste un tableau
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string // retourne une valeur qui sera de type string
    {
        return $this->name;
    }

    public function setName(string $name): self //retourne une valeur string et sera une instance de Tag. le ? juste devant string pour dire que cette valeur peut être nul
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection // Tableau d'articles
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self // ajouter un élément par un
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->articles->removeElement($article);

        return $this;
    }
}
