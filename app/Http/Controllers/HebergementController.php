<?php

namespace App\Http\Controllers;

use App\Models\Hebergement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

class HebergementController extends Controller
{

    //get ALL

    public function get_all_hebergements()
    {
        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
                'city',
                'destination_id',
                'type_id',
                'code',
                'description',
                'image'
            )
            ->get();

        $destinations = DB::table('destinations')
            ->select(
                'id',
                'name',
            )
            ->get();

        $types = DB::table('types')
            ->select(
                'id',
                'type',
            )
            ->get();

        foreach ($hebergements as $hebergement) {
            $dest_id = $hebergement->destination_id;
            $type_id = $hebergement->type_id;
            foreach ($destinations as $destination) {
                if ($dest_id == $destination->id) {
                    $hebergement->name_destination = $destination->name;
                }
            }

            foreach ($types as $type) {
                if ($type_id == $type->id) {
                    $hebergement->name_type = $type->type;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'hebergements' => $hebergements
        ], 200);
    }

    // get 1

    public function get_hebergement($id)
    {
        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
                'city',
                'destination_id',
                'type_id',
                'code',
                'description',
                'image'
            )
            ->where('id', $id)
            ->get();

        foreach ($hebergements as $hebergement) {
            $dest_id = $hebergement->destination_id;

            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                )
                ->where('id', $dest_id)
                ->get();

            foreach ($destination as $dest) {
                $hebergement->name_destination = $dest->name;
            }

            $image = Storage::disk('s3')->url($hebergement->image);
        }

        foreach ($hebergements as $hebergement) {
            $type_id = $hebergement->type_id;

            $type = DB::table('types')
                ->select(
                    'id',
                    'type',
                )
                ->where('id', $type_id)
                ->get();

            foreach ($type as $typ) {
                $hebergement->name_type = $typ->type;
            }
        }

        return response()->json([
            'message' => 'OK',
            'hebergements' => $hebergements,
            'image' => $image
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_hebergement(Request $req)
    {

        $image = Storage::disk('s3')->put('images', $req->image);

        $requete = json_decode($req->hebergement);

        $hebergement = Hebergement::create([
            'name' => $requete->name,
            'city' => $requete->city,
            'description' => $requete->description,
            'image' => $image,
            'type_id' => $requete->type,
            'code' => $requete->code,
            'destination_id' => $requete->destination_id
        ]);

        return response()->json([
            'message' => 'OK',
            'hebergement' => $hebergement
        ], 200);
    }

    // Delete

    public function delete_hebergement($id)
    {

        $plannings = DB::table('plannings')
             ->select('id')
             ->where('hebergement_id', $id)
             ->get();

        foreach($plannings as $planning) {

            $planning_id = $planning->id;
            $period = DB::table('periods')
            ->where('planning_id', $planning_id)
            ->delete();
        }

        $plan = DB::table('plannings')
            ->where('hebergement_id', $id)
            ->delete();

        $hebergement = DB::table('hebergements')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'hebergement' => $hebergement
        ], 200);
    }

    // Update

    public function modify_hebergement(Request $req)
    {

        $image = Storage::disk('s3')->put('images', $req->image);

        $requete = json_decode($req->hebergement);

        $hebergement = Hebergement::where('id', $requete->id)->update(
            [
                'name' => $requete->name,
                'city' => $requete->city,
                'destination_id' => $requete->destination_id,
                'type_id' => $requete->type_id,
                'description' => $requete->description,
                'image' => $image,
            ]
        );

        return response()->json([
            'message' => 'OK',
            'hebergement' => $hebergement
        ], 200);
    }
}
