<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Marker;
use App\Repository\MarkerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MarkerController extends Controller
{
    /**
     * Available types for custom markers
     * @var array
     */
    const TYPES = [
        'Blank' => 'blank',
        'Circle' => 'circle',
        'Diamond' => 'diamond',
        'Square' => 'square',
        'Stars' => 'stars',
    ];

    /**
     * Available colors for custom markers
     * @var array
     */
    const COLORS = [
        'Blue' => 'blu',
        'Red' => 'red',
        'Green' => 'grn',
        'Pink' => 'pink',
        'Purple' => 'purple',
        'White' => 'white',
    ];

    /**
     * API key for Google Maps API from .env
     * @var [type]
     */
    private $api_key;

    /**
     * Constructor
     * @param string $api_key
     */
    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @Route("/marker", name="markers")
     * @param MarkerRepository $markers
     */
    public function index(MarkerRepository $markers)
    {

        return $this->render('marker/index.html.twig', [
            'markers' => $markers->findAll(),
        ]);
    }

    /**
     * @Route("/marker/{id}/show", name="show_marker")
     * @param Marker $marker
     */
    public function showMarker(Marker $marker)
    {
        return $this->render('marker/show.html.twig', [
            'marker' => $marker,
            'API_KEY' => $this->getApiKey(), // For requesting maps javascript
        ]);
    }

    /**
     * @Route("/marker/my", name="my_markers")
     * @param MarkerRepository $markers
     */
    public function myMarkers(MarkerRepository $markers)
    {
        return $this->render('marker/index.html.twig', [
            'markers' => $markers->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/marker/add/{id}", name="add_marker")
     * @param  Location $location maps {id} for Location
     * @param  Request  $request
     */
    public function addMarker(Location $location, Request $request)
    {

        $defaults = [
            'color' => 'blu',
            'type' => 'blank',
            'message' => 'Hello world!',
        ];

        $form = $this->createFormBuilder($defaults)
            ->add('message', TextType::class)
            ->add('type', ChoiceType::class, ['choices' => self::TYPES])
            ->add('color', ChoiceType::class, ['choices' => self::COLORS])
            ->add('update', SubmitType::class, ['label' => 'Update Marker'])
            ->add('save', SubmitType::class, ['label' => 'Save Marker'])
            ->getForm();

        $form->handleRequest($request);

        $marker = new Marker($location);
        $marker->setUser($this->getUser());
        $marker->setLocation($location);
        $marker->setMessage($form->get('message')->getData());
        $marker->setColor($form->get('color')->getData());
        $marker->setType($form->get('type')->getData());

        if ($form->isSubmitted() && $form->isValid() && isset($request->request->get('form')['save'])) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($marker);
            $em->flush();
        }

        return $this->render('marker/add.html.twig', [
            'location' => $location,
            'marker' => $marker,
            'API_KEY' => $this->getApiKey(),
            'form' => $form->createView(),
        ]);
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
public function setApiKey(string $api_key)
{
    $this->api_key = $api_key;

    return $this;
}

}
