<?php

declare(strict_types=1);

namespace Meetup\Form;


use Zend\Filter\DateTimeSelect;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Callback;
use Zend\Validator\Date;
use Zend\Validator\StringLength;

class MeetupForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('meetup');

        $this->add([
            'type' => Element\Text::class,
            'name' => 'title',
            'options' => [
                'label' => 'Title',
                'class' => 'class="form-control"'
            ],
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => 'Description',
                'class' => 'class="form-control"'
            ],
        ]);

        $this->add([
            'type' => Element\DateTimeSelect::class,
            'name' => 'startDate',
            'options' => [
                'label' => 'Starting Date',
                'class' => 'class="form-control"'
            ],
        ]);

        $this->add([
            'type' => Element\DateTimeSelect::class,
            'name' => 'endDate',
            'options' => [
                'label' => 'Ending Date',
                'class' => 'class="form-control"'
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-primary'
            ],
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [
            'title' => [
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 2,
                            'max' => 15,
                        ],
                    ],
                ],
            ],
            'description' => [
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 5,
                            'max' => 200,
                        ],
                    ],
                ],
            ],
            'startDate' => [
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            "messages" => [Callback::INVALID_VALUE => 'Start date is too old, it must be at least today\'s date'],
                            "callback" => function($value) {
                                $today = new \DateTimeImmutable();
                                $startDate = new \DateTimeImmutable($value);

                                if($startDate < $today) {

                                    return false;
                                }

                                return true;
                            },
                        ],
                    ],
                ],
            ],
            'endDate' => [
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            "messages" => [Callback::INVALID_VALUE => 'End date can\'t be lower than Start date'],
                            "callback" => function($value, $context) : bool
                            {
                                $startDate = $context['startDate']['year'].'-'.
                                    $context['startDate']['month'].'-'.
                                    $context['startDate']['day'].' '.
                                    $context['startDate']['hour'].':'.
                                    $context['startDate']['minute'];

                                $endDate = new \DateTimeImmutable($value);
                                $startDate = new \DateTimeImmutable($startDate);

                                if($endDate < $startDate) {

                                    return false;
                                }

                                return true;
                            },
                        ],
                    ],
                ],
            ],
        ];
    }
}
