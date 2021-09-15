<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\MovieHasTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieHasTypeRepository::class)
 * @ORM\Table(name="movie_has_type")
 */
class MovieHasType
{

    /**
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @var Movie_id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\Column(name="Movie_id")
     */
    private $movie_id;

    /**
     * @var Type_id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\Column(name="Type_id")
     */
    private $type_id;

    
    public function getMovieId(): ?int
    {
        return $this->movie_id;
    }

    public function setMovieId(int $movie_id): self
    {
        $this->movie_id = $movie_id;

        return $this;
    }

    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;

        return $this;
    }
}
