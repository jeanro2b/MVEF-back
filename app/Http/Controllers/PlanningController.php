<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Planning;
use App\Models\Hebergement;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get_all_plannings()
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
            )
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
            )
            ->get();

        $plannings = Planning::all();




        foreach ($plannings as $planning) {
            $user_id = $planning->user_id;
            $hebergement_id = $planning->hebergement_id;

            foreach ($hebergements as $hebergement) {
                if ($hebergement->id == $hebergement_id) {
                    $planning->hebergement_name = $hebergement->name;
                }
            }

            foreach ($users as $user) {
                if ($user->id == $user_id) {
                    $planning->user_name = $user->name;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'plannings' => $plannings
        ], 200);
    }

    public function get_planning_client($id)
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
            )
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
            )
            ->get();

        $plannings = DB::table('plannings')
            ->select(
                'id',
                'object',
                'code',
                'status',
                'lit',
                'toilette',
                'menage',
                'user_id',
                'hebergement_id'
            )
            ->where('user_id', $id)
            ->get();

        foreach ($plannings as $planning) {
            $user_id = $planning->user_id;
            $hebergement_id = $planning->hebergement_id;

            foreach ($hebergements as $hebergement) {
                if ($hebergement->id == $hebergement_id) {
                    $planning->hebergement_name = $hebergement->name;
                }
            }

            foreach ($users as $user) {
                if ($user->id == $user_id) {
                    $planning->user_name = $user->name;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'plannings' => $plannings
        ], 200);
    }

    public function get_planning($id)
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
            )
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
            )
            ->get();

        $plannings = DB::table('plannings')
            ->select(
                'object',
                'code',
                'status',
                'lit',
                'toilette',
                'menage',
                'user_id',
                'hebergement_id'
            )
            ->where('id', $id)
            ->get();




        foreach ($plannings as $planning) {
            $user_id = $planning->user_id;
            $hebergement_id = $planning->hebergement_id;

            foreach ($hebergements as $hebergement) {
                if ($hebergement->id == $hebergement_id) {
                    $planning->hebergement_name = $hebergement->name;
                }
            }

            foreach ($users as $user) {
                if ($user->id == $user_id) {
                    $planning->user_name = $user->name;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'planning' => $plannings
        ], 200);
    }


    public function delete_planning($id)
    {

        $plannings = Planning::where('id', $id)->get();
        foreach ($plannings as $planning) {
            Period::where('planning_id', $planning->id)->delete();
        }
        Planning::where('id', $id)->delete();

        return response()->json([
            'message' => 'OK',
            'planning' => $planning
        ], 200);
    }

    public function create_planning(Request $req)
    {

        $planning = Planning::create([
            'object' => $req->object,
            'code' => $req->code,
            'status' => 'En cours',
            'lit' => $req->lit,
            'toilette' => $req->toilette,
            'menage' => $req->menage,
            'hebergement_id' => $req->hebergement_id,
            'user_id' => $req->user_id,

        ]);

        return response()->json([
            'message' => 'OK',
            'planning' => $planning
        ], 200);
    }

    public function modify_planning(Request $req)
    {

        $planning = Planning::where('id', $req->id)->update(
            [
                'object' => $req->name,
                'code' => $req->code,
                'lit' => $req->lit,
                'toilette' => $req->toilette,
                'menage' => $req->menage,
                'hebergement_id' => $req->hebergement_id,
                'user_id' => $req->user_id,
                'status' => $req->status,
            ]
        );

        return response()->json([
            'message' => 'OK',
            'planning' => $planning
        ], 200);
    }

    public function get_planningId_dest($id) {

        $planning = Planning::where('id', $id)->get();

        Log::debug($planning);


        if ($planning) {
            foreach($planning as $plan) {
                $hebergement_id = $plan->hebergement_id;
                // Faites ce que vous devez faire avec $hebergement_id
                $hebergement = Hebergement::where('id', $hebergement_id)->get();
                if ($hebergement) {
                    foreach($hebergement as $heb) {
                        $destination_id = $heb->destination_id;
                        $destination = Destination::where('id', $destination_id)->get();
                        foreach($destination as $dest) {
                            $services = Services::where('destination_id', $dest->id);
                        }
                    }
                    
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'destination' => $destination,
            'services' => $services
        ], 200);
    }
}