<?php

namespace App\Http\Controllers;

use App\Models\Destination;
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

        //tester voir la tet de la sortie
        // ajouter à chaque destination une cle nombre avec le nombre d'hebergements pour id = destination_id

        return $destinations;
    }

    public function get_destination($id)
    {

        $hebergement = DB::table('hebergements')
            ->select(
                'id',
            )
            ->where('destination_id', $id)
            ->get();

        // compter le nombre 

        $destinations = DB::table('destinations')
            ->select(
                'id',
                'name',
                'city',
            )
            ->where('id', $id)
            ->get();

        return $destinations;
    }

    public function delete_destination($id)
    {
        $destination = DB::table('destination')
            ->where('id', $id)
            ->delete();
        return $destination;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        //
    }
}
