<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class SyncController extends Controller {
    public function sync() {

        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        # currently filtering by office location
        $queryParams = array(
            '$select' => 'givenName,surname,jobTitle,department,userPrincipalName',
            '$filter' => 'officeLocation eq ' . env('OFFICE_LOCATION'),
            '$orderby' => 'givenName ASC',
            '$top' => 1000,
        );

        // Append query parameters to the '/me/events' url
        $getEventsUrl = '/me/people?' . http_build_query($queryParams);

        $events = $graph->createRequest('GET', $getEventsUrl)
        # using microsoft's models for pretty printing, can be removed if no UI needed
            ->setReturnType(Model\Person::class)
            ->execute();

        $viewData['events'] = $events;
        return view('sync', $viewData);
    }
}