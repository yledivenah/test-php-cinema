<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\MovieHasPeopleRepository;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass=MovieHasPeopleRepository::class)
 * @ORM\Table(name="movie_has_people")
 */
class MovieHasPeople
{
    
    const ARRAY_SIGNIFICANCE = ['principal','secondaire'];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\Column(name="Movie_id")
     */
    private $movie_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\Column(name="People_id")
     */
    private $people_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "enum"={"principal", "secondaire"},
     *             "example"="one"
     *         }
     *     }
     * )
     */
    private $significance;


    public function getMovieId(): ?int
    {
        return $this->movie_id;
    }

    public function setMovieId(int $movie_id): self
    {
        $this->movie_id = $movie_id;

        return $this;
    }

    public function getPeopleId(): ?int
    {
        return $this->people_id;
    }

    public function setPeopleId(int $people_id): self
    {
        $this->people_id = $people_id;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSignificance(): ?string
    {
        return $this->significance;
    }

    public function setSignificance(?string $significance): self
    {
        $this->significance = $significance;

        return $this;
    }

    
}
