<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\Pointer;
use AtlasVG\Helpers\Token;
use Illuminate\Support\Facades\Log;
use Microsoft\Graph\Graph;

/**
 */
class RemoteData
{
    /**
     * Sync info for all existing pointers
     * @return array $result counts of successful and failed syncs
     */
    public static function sync()
    {
        
        $token = new Token();
        $accessToken = $token->getAccessToken();

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

        $response = $graph->createRequest('GET', $getUsersUrl)
            ->addHeaders(array("Content-Type" => "application/json"))
            ->execute();

        $users = collect($response->getBody()['value']);

        $result = array(
            "total" => Pointer::count(),
            "successful" => 0,
            "failed"=> 0
        );

        # TODO: get only pointers with category "Person"
        Pointer::all()->each(function (Pointer $pointer) use ($users, &$result) {

            $filtered = $users->filter(function($value) use ($pointer){

                if (strtolower($value['userPrincipalName']) == strtolower($pointer->meta)) {
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