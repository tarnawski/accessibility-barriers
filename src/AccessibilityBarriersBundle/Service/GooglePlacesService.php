<?php

namespace AccessibilityBarriersBundle\Service;

use GuzzleHttp\Client;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GooglePlacesService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string;
     */
    private $GoogleApiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->GoogleApiKey = $apiKey;
    }

    /**
     * @param $lat
     * @param $lng
     * @return mixed|string
     */
    public function getPlaceName($lat, $lng)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $url = sprintf(
            'https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s&key=%s',
            $lat,
            $lng,
            $this->GoogleApiKey
        );
        $response = $this->client->get($url, ['verify' => false]);
        $fetchData = json_decode($response->getBody()->getContents(), true);
        if ($this->isValid($fetchData)) {
            $place = $accessor->getValue($fetchData, '[results][0][formatted_address]');
        }

        return isset($place) ? $place : '';
    }

    /**
     * @param $data
     * @return bool
     */
    private function isValid($data)
    {
        if (isset($data['status']) && $data['status'] == 'OK') {
            return true;
        }

        return false;
    }
}
