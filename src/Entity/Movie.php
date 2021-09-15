<?php

namespace App\Entity;


use App\Repository\MovieRepository;
use App\Repository\MovieHasPeopleRepository;
use App\Repository\PeopleRepository;
use App\Repository\MovieHasTypeRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;




/**
 *
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    // array peoples non persistant
    private $peoples;

    // array type
    private $types;

    // string
    private $urlImage;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPeoples() {
        return $this->peoples;
    }

    public function setPeoples(array $peoples) {
        $this->peoples = $peoples ;
    }


    public function setTypes(array $types) {
        $this->types = $types ;
    }

    public function getTypes() {
        return $this->types;
    }

    public function setUrlImage(string $urlImage) {
        $this->urlImage = $urlImage ;
    }

    public function getUrlImage() {
        return $this->urlImage;
    }

    
}
