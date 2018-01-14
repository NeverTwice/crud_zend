<?php

declare(strict_types=1);

namespace Meetup\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Date;

/**
 * Class Meetup
 *
 * Attention : Doctrine génère des classes proxy qui étendent les entités, celles-ci ne peuvent donc pas être finales !
 *
 * @package Meetup\Entity
 * @ORM\Entity(repositoryClass="\Meetup\Repository\MeetupRepository")
 * @ORM\Table(name="meetups")
 */
class Meetup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     **/
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2000, nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $endDate;

    public function __construct(string $title,
                                string $description = '',
                                \DateTimeImmutable $startDate = NULL,
                                \DateTimeImmutable $endDate = NULL)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function exchangeArray(array $data) : void
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->startDate = (isset($data['startDate'])) ? new \DateTimeImmutable($data['startDate']) : null;
        $this->endDate = (isset($data['endDate'])) ? new \DateTimeImmutable($data['endDate']) : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate() : \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->startDate->format(DATE_ATOM));
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate() : \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->endDate->format(DATE_ATOM));
    }

    /**
     * @param string $id
     */
    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    /**
     * @param \DateTimeImmutable $startDate
     */
    public function setStartDate(\DateTimeImmutable $startDate) : void
    {
        $this->startDate = $startDate;
    }


    /**
     * @param \DateTimeImmutable $endDate
     */
    public function setEndDate(\DateTimeImmutable $endDate) : void
    {
        $this->endDate = $endDate;
    }


}
