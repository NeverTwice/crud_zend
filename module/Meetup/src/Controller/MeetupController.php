<?php

declare(strict_types=1);

namespace Meetup\Controller;

use Meetup\Repository\MeetupRepository;
use Meetup\Form\MeetupForm;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

final class MeetupController extends AbstractActionController
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var MeetupForm
     */
    private $meetupForm;

    public function __construct(MeetupRepository $meetupRepository, MeetupForm $meetupForm)
    {
        $this->meetupRepository = $meetupRepository;
        $this->meetupForm = $meetupForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'meetups' => $this->meetupRepository->findAll(),
        ]);
    }

    public function viewAction()
    {
        return new ViewModel([
            'meetup' => $this->meetupRepository->find($this->params('id')),
        ]);
    }

    public function addAction()
    {
        $form = $this->meetupForm;

        /* @var $request Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $meetup = $this->meetupRepository->createMeetupFromNameDescriptionStartdateAndEnddate(
                    $form->getData()['title'],
                    $form->getData()['description'] ?? '',
                    new \DateTimeImmutable($form->getData()['startDate']),
                    new \DateTimeImmutable($form->getData()['endDate'])
                );
                $this->meetupRepository->add($meetup);
                return $this->redirect()->toRoute('meetups');
            }
        }

        $form->prepare();

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $form = $this->meetupForm;
        $meetup = $this->meetupRepository->find($this->params('id'));
        $form->bind($meetup);

        /* @var $request Request */

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $data['id'] = $form->getData()->getId();
                $data['title'] = $form->getData()->getTitle();
                $data['description'] = $form->getData()->getDescription();
                $data['startDate'] = $form->get('startDate')->getValue();
                $data['endDate'] = $form->get('endDate')->getValue();

                $meetup->exchangeArray($data);
                $this->meetupRepository->save($meetup);

                return $this->redirect()->toRoute('meetups/view', ['id' => $meetup->getId()]);
            }
        }

        $form->prepare();

        return new ViewModel([
            'meetup' => $meetup,
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $meetup = $this->meetupRepository->find($this->params('id'));
        $this->meetupRepository->delete($meetup);

        return $this->redirect()->toRoute('meetups');
    }
}
