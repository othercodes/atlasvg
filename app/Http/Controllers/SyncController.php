<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use AtlasVG\Models\Space;
use AtlasVG\Models\Category;
use AtlasVG\Models\Level;
use Log;

class SyncController extends Controller {

    public function sync() {

        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $local_mapping = json_decode('[{
                "space": "1.07",
                "userPrincipalName": "Katya.Gordeeva@ingrammicro.com"
            },
            {
                "space": "1.09",
                "userPrincipalName": "Sofia.Herrera@ingrammicro.com"
            },
            {
                "space": "1.08",
                "userPrincipalName": "Pablo.GarciadelosSalmones@ingrammicro.com"
            },
            {
                "space": "1.03",
                "userPrincipalName": "Esther.Mora@ingrammicro.com"
            },
            {
                "space": "1.02",
                "userPrincipalName": "Isaac.Jimeno@ingrammicro.com"
            },
            {
                "space": "1.06",
                "userPrincipalName": "Bzzz.Rzzzz@ingrammicro.com"
            }
        ]');

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
        
        # looping over all our mappings
        foreach ($local_mapping as $mapping) {

            Log::info("Found a space: " . json_encode($mapping, JSON_PRETTY_PRINT));

            $filter = $collection->filter(function($value, $key) use ($mapping){

                if ($value['userPrincipalName'] == $mapping->userPrincipalName) {
                    Log::info("Match!!");
                    return true;
                }
            });

            $user = $filter->first();

            if (isset($user)) {

                Log::info("Found a match: " . json_encode($user, JSON_PRETTY_PRINT));

                $levels = Level::where('level', '=', 1);
                $level = $levels->first();
                Log::info("Found a level: " . json_encode($level, JSON_PRETTY_PRINT));

                $spaces = Space::where('data', '=', $mapping->space);
                $space = $spaces->first();
                Log::info("Found a space: " . json_encode($space, JSON_PRETTY_PRINT));

                $center = $level->calculateRelativeSpaceCenter($space);

                $category = Category::inRandomOrder()->first();

                $pointer = new \AtlasVG\Models\Pointer([
                    'name' => $user['surname'],
                    'meta' => $user['userPrincipalName'],
                    'description' => $user['jobTitle'],
                    'top' => $center['y'],
                    'left' => $center['x']
                ]);
                $pointer->space()->associate($space);
                $pointer->category()->associate($category);
                $pointer->save();

            }

        }

        return response()->json($users->getBody());

        #$viewData['users'] = $users;
        #return view('sync', $viewData);
    }
}