<?php
// src/DataPersister/EventDataPersister.php

namespace App\DataPersister;

use App\Entity\Event;
use App\Entity\UserHasEvent;
use App\Service\GoogleService;
use Google_Service_Calendar;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class EventDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $googleService;

    public function __construct(EntityManagerInterface $entityManager, GoogleService $googleService)
    {
        $this->_entityManager = $entityManager;
        $this->googleService = $googleService;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Event;
    }

    /**
     * @param Event $data
     */
    public function persist($data, array $context = [])
    {
        /*$client = $this->googleService->getClient("alexandre.peneau@gmail.com");
        $service = new Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        return new Response($events, Response::HTTP_OK);*/

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();

        if (($context['collection_operation_name'] ?? null) === 'post') {
            if ($data->isRepeat() == true) {
                $actualDate = $data->getTimeToStart();
                //dump($actualDate);
                $actualDate->add(new \DateInterval("P7D"));
                //dump($actualDate);
                //dump($data);
                while ($actualDate <= $data->getEndRepeat()) {
                    $newEvent = clone $data;
                    $newEvent->setTimeToStart($actualDate);
                    //dump($newEvent);
                    //dump($newEvent->getTimeToStart());
                    $this->_entityManager->persist($newEvent);
                    $this->_entityManager->flush();
                    $actualDate->add(new \DateInterval("P7D"));
                }
            }
        }

        //$this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}
