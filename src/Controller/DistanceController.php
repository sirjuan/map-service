<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\GoogleMaps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DistanceController extends Controller
{
    /**
     * @var string
     */
    private $distance;

    /**
     * @var string
     */
    private $duration;

    /**
     * @Route("/distance", name="distance")
     * @param Request $request
     * @param GoogleMaps $maps
     */
    public function index(Request $request, GoogleMaps $maps)
    {
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        $choices = $maps->makeChoices($locations);

        $form = $this->createFormBuilder(['caption' => ''])
            ->add('origin', ChoiceType::class, ['choices' => $choices])
            ->add('destination', ChoiceType::class, ['choices' => $choices])
            ->add('save', SubmitType::class, array('label' => 'Get distance'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $origin = $form['origin']->getData();
            $destination = $form['destination']->getData();

            $result = $maps->getDistance($origin, $destination);

            $this->setDistance($result['distance']['text']);
            $this->setDuration($result['duration']['text']);

        }
        return $this->render('distance/index.html.twig', [
            'form' => $form->createView(),
            'distance' => $this->getDistance(),
            'duration' => $this->getDuration(),
        ]);
    }

    /**
     * Get the valueof Distance
     *
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set the value of Distance
     *
     * @param mixed $distance
     *
     * @return self
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get the valueof Duration
     *
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of Duration
     *
     * @param mixed $duration
     *
     * @return self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

}
