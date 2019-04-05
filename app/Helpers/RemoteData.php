<?php

namespace AtlasVG\Helpers;

use Storage;
use AtlasVG\Models\Building;
use AtlasVG\Helpers\GraphAPI;
use Illuminate\Support\Facades\Log;

class RemoteData
{
    /**
     * Sync info for all existing pointers
     * @return array $result counts of successful and failed syncs
     */
    public static function sync_data($bid = null)
    {

        $bid = RemoteData::validate_bid($bid);
        $building = Building::find($bid);

        if (!$building->location) {
            throw new \Exception("Location is for building #{$bid} is not defined, cannot perform sync.");
        }

        $queryParams = array(
            '$select' => 'givenName,surname,jobTitle,department,userPrincipalName',
            '$filter' => "officeLocation eq '$building->location'",
            '$top' => 1000,
        );

        $getUsersUrl = '/me/people?' . http_build_query($queryParams);

        $api = new GraphAPI($bid);
        $response = $api->sendRequest($getUsersUrl);
        $users = collect($response['value']);

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

                        Log::debug("Found a user: {$match['userPrincipalName']}.");

                        $pointer->name = "{$match['givenName']} {$match['surname']}";
                        $pointer->description = "Job Title: {$match['jobTitle']} <br> Department: {$match['department']}";
                        $pointer->save();

                        $result['successful']++;

                    } else {

                        Log::debug("Couldn't find user with email: {$pointer->meta}, retrieving info manually");

                        try {

                            $getUserByEmailUrl = '/users/' . $pointer->meta;
                            $user = $api->sendRequest($getUserByEmailUrl);

                            # without admin context that API endpoint returns only given name and surname 
                            $pointer->name = "{$user['givenName']} {$user['surname']}";
                            $pointer->save();

                            $result['successful']++;

                        } catch (\GuzzleHttp\Exception\ClientException $exception) {

                            if ($exception->getResponse()->getStatusCode() == "404") {
                                Log::warning("User with email address {$pointer->meta} doesn't exist.");
                                $result['failed']++;
                            } else {
                                throw $exception;
                            }
                        }
                        
                    }

                }
            }
        }

        return $result;
    }

    public static function sync_photos($bid = null) {

        $bid = RemoteData::validate_bid($bid);
        $building = Building::find($bid);

        $api = new GraphAPI($bid);

        foreach ($building->levels as $level) {

            foreach ($level->pointers as $pointer) {

                $email = $pointer->meta;

                $getPhotoUrl = '/users/' . $email . '/photos/120x120/$value';
        
                try {

                    $photo = $api->sendRequest($getPhotoUrl, true);
                    Storage::disk('public')->put('img/' . $level->id . '.' . $pointer->space->data . '.jpg', $photo);

                } catch (\GuzzleHttp\Exception\ClientException $exception) {

                    if ($exception->getResponse()->getStatusCode() == "404") {
                        Log::info("This user doesn't have an avatar");
                    } elseif ($exception->getResponse()->getStatusCode() == "400") {
                        Log::warning("User with email address {$pointer->meta} doesn't exist.");
                    } else {
                        throw $exception;
                    }
                }

            }
        }   
    }


    /**
     * Sync info for all existing pointers
     * @return array $result counts of successful and failed syncs
     */
    private static function validate_bid($bid = null) {

        # if no building id is passed, defaulting to the first one
        if (!$bid) {
            $bid = Building::select()->first()->id;
        # if building id is specified but doesn't exist throwing 404
        } elseif (!Building::find($bid)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $bid;
    }

}