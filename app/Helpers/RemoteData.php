<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\Pointer;
use AtlasVG\TokenStore\TokenCache;
use Illuminate\Support\Facades\Log;
use Microsoft\Graph\Graph;

/**
 */
class RemoteData
{
    /**
     */
    public static function sync()
    {
        
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        # currently filtering by office location
        # TODO: handle cases if office location is not specified
        # TODO: handle cleaning & escaping special characters in OFFICE_LOCATION
        $queryParams = array(
            '$select' => 'givenName,surname,jobTitle,department,userPrincipalName',
            '$filter' => 'officeLocation eq ' . env('OFFICE_LOCATION'),
            '$orderby' => 'givenName ASC',
            '$top' => 1000,
        );

        $getUsersUrl = '/me/people?' . http_build_query($queryParams);

        $users = $graph->createRequest('GET', $getUsersUrl)
            ->addHeaders(array("Content-Type" => "application/json"))
            ->execute();

        $users_cleaned = $users->getBody()['value'];
        $collection = collect($users_cleaned);

        $result = array(
            "total" => Pointer::count(),
            "successful" => 0,
            "failed"=> 0
        );

        # damn closures
        Pointer::all()->each(function (Pointer $pointer) use ($collection, &$result) {

            $filtered = $collection->filter(function($value) use ($pointer){

                if ($value['userPrincipalName'] == $pointer->meta) {
                    return true;
                }
            });

            $match = $filtered->first();

            if ($match) {

                Log::info("Found user: {$match['userPrincipalName']}.");

                $pointer->name = "{$match['givenName']} {$match['surname']}";
                $pointer->description = "Job Title: {$match['jobTitle']} <br> Department: {$match['department']}";
                $pointer->save();

                $result['successful']++;

            } else {

                Log::critical("Couldn't find user with email: {$pointer->meta}.");
                $result['failed']++;

            }
        });

        return $result;

    }
}