<?php

declare(strict_types=1);

namespace Meetup\Controller\Factory;

use Meetup\Controller;
use Meetup\Form\MeetupForm;
use Doctrine\ORM\EntityManager;
use Meetup\Entity\Meetup;
use Psr\Container\ContainerInterface;

final class MeetupControllerFactory
{
    public function __invoke(ContainerInterface $container) : Controller\MeetupController
    {
        $meetupRepository = $container->get(EntityManager::class)->getRepository(Meetup::class);
        $meetupForm = $container->get(MeetupForm::class);

        return new Controller\MeetupController($meetupRepository, $meetupForm);
    }
}
