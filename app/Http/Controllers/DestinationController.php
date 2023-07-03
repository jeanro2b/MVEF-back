<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Retours;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{

    public function get_all_destinations(Request $req)
    {
        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'destination_id',
                'price',
                'type_id',
                'couchage'
            )
            ->get();


        $destinations = DB::table('destinations')
            ->select(
                'id',
                'name',
                'city',
                'favorite',
                'description',
                'pImage',
                'location',
            )
            ->get();

        $plannings = DB::table('plannings')
            ->select(
                'id',
                'hebergement_id',
            )
            ->get();

        $periods = DB::table('periods')
            ->select(
                'id',
                'start',
                'end',
                'planning_id'
            )
            ->get();

        foreach ($destinations as $destination) {
            $nombre_herbegements = 0;
            $min_price = 0;
            $appartement = false;
            $mobil_home = false;
            $villa = false;
            $types = [];
            $couchages = [];


            foreach ($hebergements as $hebergement) {
                if ($hebergement->destination_id == $destination->id) {
                    $nombre_herbegements++;
                    array_push($couchages, strval($hebergement->couchage));
                    if ($hebergement->type_id == 1) {
                        $appartement = true;
                    }
                    if ($hebergement->type_id == 2) {
                        $mobil_home = true;
                    }
                    if ($hebergement->type_id == 3) {
                        $villa = true;
                    }
                }
                if ($hebergement->destination_id == $destination->id && $hebergement->price > $min_price) {
                    $min_price = $hebergement->price;
                }
            }

            if ($appartement == true) {
                array_push($types, 'Appartement');
            }
            if ($mobil_home == true) {
                array_push($types, 'MobilHome');
            }
            if ($villa == true) {
                array_push($types, 'Villa');
            }

            $destination->min_price = $min_price;
            $destination->nombre = $nombre_herbegements;
            $destination->types = $types;
            $destination->couchage = $couchages;
        }

        if ($req->start_date) {
            //faire les filtres dates


            foreach ($destinations as $destination) {
                $dispo = true;
                foreach ($hebergements as $hebergement) {
                    if ($hebergement->destination_id == $destination->id) {
                        foreach ($plannings as $planning) {
                            if ($planning->hebergement_id == $hebergement->id) {
                                foreach ($periods as $period) {
                                    if ($period->start <= $req->start_date && $period->end >= $req->end_date) {
                                        $dispo = false;
                                    }
                                }
                            }
                        }
                    }
                }

                $destination->dispo = $dispo;
            }
        }


        return $destinations;
    }

    public function get_destination($id)
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
                'price',
                'pImage',
                'sImage',
                'tImage',
                'description',
                'couchage',
                'long_title'
            )
            ->where('destination_id', $id)
            ->get();

        $equipements = DB::table('equipements')
            ->select(
                'id',
                'text',
                'hebergement_id'
            )
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
            ->where('id', $id)
            ->get();

        foreach ($hebergements as $hebergement) {
            $equips = [];
            foreach ($equipements as $equipement) {
                if ($equipement->hebergement_id == $hebergement->id) {
                    array_push($equips, $equipement);
                }
            }
            $hebergement->equipements = $equips;

            $hp_Image = Storage::disk('s3')->url($hebergement->pImage);
            $hs_Image = Storage::disk('s3')->url($hebergement->sImage);
            $ht_Image = Storage::disk('s3')->url($hebergement->tImage);

            $hebergement->pImage = $hp_Image;
            $hebergement->sImage = $hs_Image;
            $hebergement->tImage = $ht_Image;
        }


        foreach ($destinations as $dest) {
            $p_Image = Storage::disk('s3')->url($dest->pImage);
            $s_Image = Storage::disk('s3')->url($dest->sImage);
            $t1_Image = Storage::disk('s3')->url($dest->tImage1);
            $t2_Image = Storage::disk('s3')->url($dest->tImage2);
        }

        if (isset($p_Image) && isset($s_Image) && isset($t1_Image) && isset($t2_Image)) {
            return response()->json([
                'message' => 'OK',
                'destinations' => $destinations,
                'hebergements' => $hebergements,
                'services' => $services,
                'retours' => $retours,
                'pImage' => $p_Image ? $p_Image : '',
                'sImage' => $s_Image ? $s_Image : '',
                'tImage1' => $t1_Image ? $t1_Image : '',
                'tImage2' => $t2_Image ? $t2_Image : ''
            ], 200);
        } else {
            return response()->json([
                'message' => 'OK',
                'destinations' => $destinations,
                'hebergements' => $hebergements,
                'services' => $services,
                'retours' => $retours,
            ], 400);
        }
    }

    public function delete_destination($id)
    {

        Service::where('destination_id', $id)->delete();
        Retours::where('destination_id', $id)->delete();

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

        $pImage = Storage::disk('s3')->put('images', $req->pImage);
        $sImage = Storage::disk('s3')->put('images', $req->sImage);
        $tImage1 = Storage::disk('s3')->put('images', $req->tImage1);
        $tImage2 = Storage::disk('s3')->put('images', $req->tImage2);

        $requete = json_decode($req->destination);

        $destination = Destination::create([
            'name' => $requete->name,
            'city' => $requete->city,
            'description' => $requete->description,
            'address' => $requete->address,
            'latitude' => $requete->latitude,
            'longitude' => $requete->longitude,
            'phone' => $requete->phone,
            'languages' => $requete->languages,
            'mail' => $requete->mail,
            'reception' => $requete->reception,
            'arrival' => $requete->arrival,
            'departure' => $requete->departure,
            'carte' => $requete->carte,
            'pImage' => $pImage,
            'sImage' => $sImage,
            'tImage1' => $tImage1,
            'tImage2' => $tImage2,
            'vehicule' => $requete->vehicule,
            'parking' => $requete->parking,
            'favorite' => $requete->favorite,
            'location' => $requete->location,
        ]);


        foreach ($requete->services as $service) {
            Service::create([
                'text' => $service,
                'destination_id' => $destination->id
            ]);
        }

        foreach ($requete->retours as $retour) {
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
        $pImage = Storage::disk('s3')->put('images', $req->pImage);
        $sImage = Storage::disk('s3')->put('images', $req->sImage);
        $tImage1 = Storage::disk('s3')->put('images', $req->tImage1);
        $tImage2 = Storage::disk('s3')->put('images', $req->tImage2);

        $requete = json_decode($req->destination);

        $destination = Destination::where('id', $requete->id)->update(
            [
                'name' => $requete->name,
                'city' => $requete->city,
                'description' => $requete->description,
                'address' => $requete->address,
                'latitude' => $requete->latitude,
                'longitude' => $requete->longitude,
                'phone' => $requete->phone,
                'languages' => $requete->languages,
                'mail' => $requete->mail,
                'reception' => $requete->reception,
                'arrival' => $requete->arrival,
                'departure' => $requete->departure,
                'carte' => $requete->carte,
                'pImage' => $pImage,
                'sImage' => $sImage,
                'tImage1' => $tImage1,
                'tImage2' => $tImage2,
                'vehicule' => $requete->vehicule,
                'parking' => $requete->parking,
                'favorite' => $requete->favorite,
                'location' => $requete->location,
            ]
        );

        Service::where('destination_id', $requete->id)->delete();
        Retours::where('destination_id', $requete->id)->delete();

        foreach ($requete->services as $service) {
            Service::create([
                'text' => $service,
                'destination_id' => $requete->id
            ]);
        }

        foreach ($requete->retours as $retour) {
            Retours::create([
                'text' => $retour,
                'destination_id' => $requete->id
            ]);
        }



        return response()->json([
            'message' => 'OK',
            'destination' => $destination,
            'pImage' => $pImage,
            'sImage' => $sImage,
            'tImage1' => $tImage1,
            'tImage2' => $tImage2,
        ], 200);
    }
}
