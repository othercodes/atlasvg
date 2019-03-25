<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\Pointer;
use AtlasVG\Models\Building;
use AtlasVG\Models\Level;
use AtlasVG\Helpers\Token;
use Illuminate\Support\Facades\Log;
use Microsoft\Graph\Graph;

class RemoteData
{
    /**
     * Sync info for all existing pointers
     * @return array $result counts of successful and failed syncs
     */
    public static function sync($bid = null)
    {

        if (!$bid) {
            $bid = Building::select()->first()->id;
        # if building id is specified but doesn't exist throwing 404
        } elseif (!Building::find($bid)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        $building = Building::find($bid);

        if (!$building->location) {
            throw new \Exception("Location is for building #{$bid} is not defined, cannot perform sync.");
        }

        $token = new Token();
        $accessToken = $token->getAccessToken($bid);

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $queryParams = array(
            '$select' => 'givenName,surname,jobTitle,department,userPrincipalName',
            '$filter' => "officeLocation eq '$building->location'",
            '$top' => 1000,
        );

        $getUsersUrl = '/me/people?' . http_build_query($queryParams);

        $response = $graph->createRequest('GET', $getUsersUrl)
            ->addHeaders(array("Content-Type" => "application/json"))
            ->execute();

        $users = collect($response->getBody()['value']);

        $result = array(
            "successful" => 0,
            "failed"=> 0
        );

        foreach ($building->levels as $level) {

            foreach ($level->pointers as $pointer) {

                # syncing data only for people
                if ($pointer->category->name == 'Person') {

                    $filtered = $users->filter(function($value) use ($pointer){

                        if (strtolower($value['userPrincipalName']) == strtolower($pointer->meta)) {
                            return true;
                        }
                    });

                    $match = $filtered->first();

                    if ($match) {

                        Log::info("Found a user: {$match['userPrincipalName']}.");

                        $pointer->name = "{$match['givenName']} {$match['surname']}";
                        $pointer->description = "Job Title: {$match['jobTitle']} <br> Department: {$match['department']}";
                        $pointer->save();

                        $result['successful']++;

                    } else {

                        Log::error("Couldn't find user with email: {$pointer->meta}.");
                        $result['failed']++;

                    }

                }
            }
        }

        return $result;
    }
}