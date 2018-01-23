<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\GoogleMaps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class NavigationController extends Controller
{
    /**
     * Bounds for map
     * @var array
     */
    private $bounds;

    /**
     * Route instructions
     * @var array
     */
    private $legs;

    /**
     * API key for Google Maps API from .env
     * @var string
     */
    private $api_key;

    /**
     * Constructor: Initialize legs and bounds arrays
     * @param string $api_key
     */
    public function __construct(string $api_key)
    {
        $this->legs = [];
        $this->bounds = [];
        $this->api_key = $api_key;
    }

    /**
     * @Route("/navigation", name="navigation")
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
            ->add('save', SubmitType::class, ['label' => 'Get directions'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $origin = $form['origin']->getData();
            $destination = $form['destination']->getData();

            $result = $maps->getDirections($origin, $destination);

            $this->setLegs($result['legs']);
            $this->setBounds($result['bounds']);

        }
        return $this->render('navigation/index.html.twig', [
            'form' => $form->createView(),
            'bounds' => $this->getBounds(),
            'legs' => $this->getLegs(),
            'API_KEY' => $this->getApiKey(),
        ]);
    }

    /**
     * Get the valueof Bounds
     *
     * @return mixed
     */
    public function getBounds()
    {
        return $this->bounds;
    }

    /**
     * Set the value of Bounds
     *
     * @param mixed $bounds
     *
     * @return self
     */
    public function setBounds($bounds)
    {
        $this->bounds = $bounds;

        return $this;
    }

    /**
     * Get the valueof Legs
     *
     * @return mixed
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * Set the value of Legs
     *
     * @param mixed $legs
     *
     * @return self
     */
    public function setLegs($legs)
    {
        $this->legs = $legs;

        return $this;
    }

/**
 * Get the valueof API key for Google Maps API from .env
 *
 * @return string
 */
    public function getApiKey()
    {
        return $this->api_key;
    }

/**
 * Set the value of API key for Google Maps API from .env
 *
 * @param string $api_key
 *
 * @return self
 */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;

        return $this;
    }

}
