<?php

declare(strict_types=1);

namespace Meetup\Repository;

use Meetup\Entity\Meetup;
use Doctrine\ORM\EntityRepository;

final class MeetupRepository extends EntityRepository
{

    public function add(Meetup $meetup) : void
    {
        $this->getEntityManager()->persist($meetup);
        $this->getEntityManager()->flush($meetup);
    }

    public function createMeetupFromNameDescriptionStartdateAndEnddate(string $title,
                                                                       string $description,
                                                                       \DateTimeImmutable $startDate,
                                                                       \DateTimeImmutable $endDate) : Meetup
    {
        return new Meetup($title, $description, $startDate, $endDate);
    }

    public function save(Meetup $meetup) : void
    {
        $this->getEntityManager()->flush($meetup);
    }

    public function delete(Meetup $meetup) : void
    {
        $this->getEntityManager()->remove($meetup);
        $this->getEntityManager()->flush($meetup);
    }

}
