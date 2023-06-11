<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Retours;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestinationController extends Controller
{

    public function get_all_destinations()
    {
        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'destination_id'
            )
            ->get();


        $destinations = DB::table('destinations')
            ->select(
                'id',
                'name',
                'city',
            )
            ->get();

        foreach ($destinations as $destination) {
            $nombre_herbegements = 0;
            foreach ($hebergements as $hebergement) {
                if ($hebergement->destination_id == $destination->id) {
                    $nombre_herbegements++;
                }
            }

            $destination->nombre = $nombre_herbegements;
        }


        return $destinations;
    }

    public function get_destination($id)
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name'
            )
            ->where('destination_id', $id)
            ->get();

        $services = DB::table('services')
            ->select(
                'id',
                'destination_id',
                'text'
            )
            ->where('destination_id', $id)
            ->get();

        $retours = DB::table('retours')
            ->select(
                'id',
                'destination_id',
                'text'
            )
            ->where('destination_id', $id)
            ->get();

        $destinations = DB::table('destinations')
            ->select(
                '*'
            )
            ->where('id', $id)
            ->get();

        return response()->json([
            'message' => 'OK',
            'destinations' => $destinations,
            'hebergements' => $hebergements,
            'services' => $services,
            'retours' => $retours
        ], 200);
    }

    public function delete_destination($id)
    {
        $destination = DB::table('destinations')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'destination' => $destination
        ], 200);
    }

    public function create_destination(Request $req)
    {

        $destination = Destination::create([
            'name' => $req->name,
            'city' => $req->city,
            'description' => $req->description,
            'address' => $req->address,
            'latitude' => $req->latitude,
            'longitude' => $req->longitude,
            'phone' => $req->phone,
            'languages' => $req->languages,
            'mail' => $req->mail,
            'reception' => $req->reception,
            'arrival' => $req->arrival,
            'departure' => $req->departure,
            'map' => $req->map,
            'pImage' => $req->pImage,
            'sImage' => $req->sImage,
            'tImage1' => $req->tImage1,
            'tImage2' => $req->tImage2,
            'vehicule' => $req->vehicule,
            'parking' => $req->parking,
        ]);


        foreach ($req->services as $service) {
            Service::create([
                'text' => $service,
                'destination_id' => $destination->id
            ]);
        }

        foreach ($req->retours as $retour) {
            Retours::create([
                'text' => $retour,
                'destination_id' => $destination->id
            ]);
        }


        return response()->json([
            'message' => 'OK',
            'destination' => $destination
        ], 200);
    }


    public function modify_destination(Request $req)
    {

        //Ajouter  services ! d'abord supprimer puis ajouiter => OK
        $destination = Destination::where('id', $req->id)->update(
            [
                'name' => $req->name,
                'city' => $req->city,
                'description' => $req->description,
                'address' => $req->address,
                'latitude' => $req->latitude,
                'longitude' => $req->longitude,
                'phone' => $req->phone,
                'languages' => $req->languages,
                'mail' => $req->mail,
                'reception' => $req->reception,
                'arrival' => $req->arrival,
                'departure' => $req->departure,
                'map' => $req->map,
                'pImage' => $req->pImage,
                'sImage' => $req->sImage,
                'tImage1' => $req->tImage1,
                'tImage2' => $req->tImage2,
                'vehicule' => $req->vehicule,
                'parking' => $req->parking,
            ]
        );

        $delete = DB::table('destination_has_service')
            ->select(
                '*'
            )
            ->where('destination_id', $req->id)
            ->delete();

        foreach ($req->services as $service) {
            $insert = DB::table('destination_has_service')
                ->insert([
                    'destination_id' => $req->id,
                    'service_id' => $service->service_id
                ]);
        }

        return response()->json([
            'message' => 'OK',
            'destination' => $destination
        ], 200);
    }
}
