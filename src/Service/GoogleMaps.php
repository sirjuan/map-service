<?php
namespace App\Service;

use Buzz\Browser;

/**
 * GeolocationApi
 *
 * Provides a simple wrapper around Google Geocoding API.
 * Based on https://github.com/dsyph3r/GoogleGeolocationBundle/blob/master/Geolocation/GeolocationApi.php
 *
 * @see http://code.google.com/apis/maps/documentation/geocoding/
 */
class GoogleMaps
{
    /**
     * Buzz/Browser for making requests on Google Maps API
     * @var Browser
     */

    private $browser;
    /**
     * API key for Google Maps API from .env
     * @var [type]
     */
    private $api_key;

    /**
     * Constructor
     * @param string $api_key
     * @param Browser|null         $browser
     */
    public function __construct(string $api_key, Browser $browser = null)
    {
        $this->browser = $browser ?: new Browser();
        $this->api_key = $api_key;
    }

    /**
     * Geolocate and populate Location entity with result
     *
     * @param   string $address
     * @return  array
     */
    public function geoLocate(string $address)
    {
        $query = ['address' => $address, 'sensor' => 'false'];
        $data = $this->request('geocode', $query);
        $status = $data['status'];
        $results = $data['results'];

        // Check if matches were found for $search
        if ($status === 'OK') {
            return $results;
        } else {
            return [];
        }

    }

    /**
     * Geolocate and populate Location entity with result
     *
     * @param string $origin placeId
     * @param string $destination placeId
     * @param string $mode
     * @return array
     */
    public function getDistance(string $origin, string $destination, $mode = 'driving')
    {
        $query = [
            'origins' => "place_id:$origin",
            'destinations' => "place_id:$destination",
            'mode' => $mode,
        ];

        $data = $this->request('distancematrix', $query);
        $status = $data['status'];
        $results = $data['rows'];
        // Check if matches were found for $search
        if ($status === 'OK') {
            return $results[0]['elements'][0];
        } else {
            return ['Nothing found'];
        }

    }

    /**
     * Get directions between two markers
     *
     * @param string $origin placeId
     * @param string $destination placeId
     * @param string $mode
     * @return array
     */
    public function getDirections(string $origin, string $destination, $mode = 'driving')
    {
        $query = [
            'origin' => "place_id:$origin",
            'destination' => "place_id:$destination",
            'mode' => $mode,
        ];

        $data = $this->request('directions', $query);
        $status = $data['status'];
        $results = $data['routes'];
        // Check if matches were found for $search
        if ($status === 'OK') {
            return $results['0'];
        } else {
            return ['Nothing found'];
        }

    }

    /**
     * Execute request to Google Maps API and return data
     * @param  string $endpoint geocode/directions/distancematrix
     * @param  array  $query
     * @return array
     */
    protected function request($endpoint = 'geocode', $query = [])
    {
        $query['key'] = $this->getApiKey();
        $response = $this->browser->get("https://maps.googleapis.com/maps/api/$endpoint/json?" .
            http_build_query($query)
        );

        return json_decode($response->getContent(), true);
    }

    /**
     * Reducer for makeChoices()
     * @param  [type] $choices  [description]
     * @param  [type] $location [description]
     * @return array ['address' => 'id']
     */
    protected function mapChoices($choices, $location)
    {
        $choices[$location->getAddress()] = $location->getId();
        return $choices;
    }

    /**
     * Formulates choices for Distance and Navigation
     * @param  mixed $locations
     * @return array   ['address' => 'id']
     */
    public function makeChoices($locations)
    {
        return array_reduce($locations, [$this, 'mapChoices'], []);
    }

/**
 * Get the valueof Buzz/Browser for making requests on Google Maps API
 *
 * @return Browser
 */
public function getBrowser()
{
    return $this->browser;
}

/**
 * Set the value of Buzz/Browser for making requests on Google Maps API
 *
 * @param Browser $browser
 *
 * @return self
 */
public function setBrowser(Browser $browser)
{
    $this->browser = $browser;

    return $this;
}

/**
 * Get the valueof API key for Google Maps API from .env
 *
 * @return [type]
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
