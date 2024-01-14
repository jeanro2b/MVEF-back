<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{

    public function create_price(Request $request)
    {

        foreach ($request->prices as $price) {
            Price::create([
                'price' => $price['price'],
                'reduction' => $price['reduction'],
                'start' => $price['start'],
                'end' => $price['end'],
                'hebergement_id' => $price['hebergement_id'],
            ]);
        }

        return response()->json([
            'message' => 'OK',
        ], 200);
    }

    public function get_prices($id)
    {
        $prices = DB::table('prices')
        ->select(
            'id',
            'hebergement_id',
            'start',
            'end',
            'price',
            'reduction'
        )
        ->where('hebergement_id', $id)
        ->get();

        return response()->json([
            'message' => 'OK',
            'prices' => $prices
        ], 200);
    }

    public function modify_prices(Request $req)
    {

        foreach ($req->prices as $price) {
            Price::where('id', $price->id)->update([
                'price' => $price['price'],
                'reduction' => $price['reduction'],
            ]);
        }
        
        return response()->json([
            'message' => 'OK',
        ], 200);
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
    public function show(Equipements $equipements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipements $equipements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipements $equipements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipements $equipements)
    {
        //
    }
}
