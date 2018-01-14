<?php

declare(strict_types=1);

use Meetup\Form\MeetupForm;
use Meetup\Entity\Meetup;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Meetup\Controller;

return [
    'router' => [
        'routes' => [
            'meetups' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/meetups',
                    'defaults' => [
                        'controller' => Controller\MeetupController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'action' => 'add'
                            ],
                        ],
                    ],
                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/view/:id',
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'action' => 'delete'
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/:id',
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\MeetupController::class => Controller\Factory\MeetupControllerFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Meetup::class => InvokableFactory::class,
            MeetupForm::class => InvokableFactory::class
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'meetup/meetup/index' => __DIR__ . '/../view/meetup/index.phtml',
            'meetup/meetup/add' => __DIR__ . '/../view/meetup/add.phtml',
            'meetup/meetup/view' => __DIR__ . '/../view/meetup/view.phtml',
            'meetup/meetup/edit' => __DIR__ . '/../view/meetup/edit.phtml',
        ],
    ],
    'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'meetup_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/../src/Entity/',
                ],
            ],

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    // register `application_driver` for any entity under namespace `Application\Entity`
                    'Meetup\Entity' => 'meetup_driver',
                ],
            ],
        ],
    ],
];
