<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Service\GoogleMaps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{

    /**
     * @var array
     */
    private $locations = [];

    /**
     * For creating named form
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Constructor
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/location", name="locations")
     * @param LocationRepository $locations
     */
    public function index(LocationRepository $locations)
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locations->findAll(),
        ]);
    }

    /**
     * @Route("/location/{id}/show", name="show_location")
     * @param Location $location
     */
    public function showLocation(Location $location)
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    /**
     * @Route("/location/{id}/edit", name="edit_location")
     * @param Location $location
     * @param Request $request
     */
    public function editLocation(Location $location, Request $request)
    {
        $form = $this->createFormBuilder(['caption' => ''])
            ->add('image', FileType::class)
            ->add('caption', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Add Image'])
            ->getForm();

        $user = $this->getUser();

        if ($location->getUser['id'] == $user->getId()) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $caption = $form['caption']->getData();
                $file = $form['image']->getData();

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory'), $fileName);

                $image = new Image();
                $image->setSrc($fileName);
                $image->setCaption($caption);
                $image->setUser($this->getUser());

                $location->addImage($image);

                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->merge($location);
                $em->flush();

            }
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/locations/my", name="my_locations")
     * @param LocationRepository $locations
     */
    public function myLocations(LocationRepository $locations)
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locations->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/location/add", name="add_location")
     * @param Request $request
     * @param GoogleMaps $maps
     * @param LocationRepository $locs
     */
    public function addLocation(Request $request, GoogleMaps $maps, LocationRepository $locs)
    {
        $defaults = ['address' => ''];

        $form = $this->createFormBuilder($defaults)
            ->add('address', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Search'])
            ->getForm();

        $form->handleRequest($request);

        if ($request->request->has('form') && $form->isSubmitted() && $form->isValid()) {

            $address = $form->get('address')->getData();
            $results = $maps->geoLocate($address);

            $locations = array_map([$this, 'initLocation'], $results);
            $this->setLocations($locations);

        } else if (count($request->request->keys()) > 0) {

            $key = $request->request->keys()[0];
            $loc = $request->request->get($key);

            $id = $loc['id'];

            $locationExists = $locs->findOneBy(['id' => $id]);

            if (null === $locationExists) {
                $location = new Location();

                $location->setLatitude($loc['latitude']);
                $location->setLongitude($loc['longitude']);
                $location->setAddress($loc['address']);
                $location->setId($id);
                $location->setUser($this->getUser());

                $this->saveLocation($location);
            }

            return $this->redirectToRoute('add_marker', [
                'id' => $id,
            ]);
        }

        return $this->render('location/search.html.twig', [
            'locations' => $this->getLocations(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Initializes location for adding it to database
     * @param  array $loc [description]
     * @return array      [description]
     */
    protected function initLocation($loc): array
    {

        $id = $loc['place_id'];
        $latLng = $loc['geometry']['location'];
        $location = new Location();
        $location = [
            'latitude' => $latLng['lat'],
            'longitude' => $latLng['lng'],
            'address' => $loc['formatted_address'],
            'id' => $id,
        ];

        $locations = $this->getDoctrine()->getRepository(Location::class);
        $existingLocation = $locations->findOneBy(['id' => $id]);
        if (null !== $location) {
            $location['user'] = $existingLocation->getUser();
        }

        $form = $this->formFactory->createNamedBuilder($id, 'Symfony\\Component\\Form\\Extension\\Core\\Type\\FormType', $location)
            ->add('address', HiddenType::class)
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('id', HiddenType::class)
            ->getForm();

        $location['form'] = $form->createView();

        return $location;
    }

    /**
     * @param Location $location
     */

    public function saveLocation(Location $location)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($location);
        $em->flush();
    }

    /**
     * Get the valueof Locations
     *
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set the value of Locations
     *
     * @param array $locations
     *
     * @return self
     */
    public function setLocations(array $locations)
    {
        $this->locations = $locations;

        return $this;
    }

}
