<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Minimum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{

    public function create_price(Request $request)
    {

        foreach ($request->prices as $price) {
            $reduction = isset($price['reduction']) && $price['reduction'] !== '' ? $price['reduction'] : 0;
            if (!is_null($price['price']) && $price['price'] !== '') {
                Price::create([
                    'price' => $price['price'],
                    'reduction' => $reduction,
                    'start' => $price['start'],
                    'end' => $price['end'],
                    'hebergement_id' => $price['hebergement_id'],
                ]);
            }

        }

        if (isset($request->minimum) && !is_null($request->minimum) && $request->minimum !== '') {
            Minimum::create([
                'minimum' => $request->minimum->min,
                'hebergement_id' => $request->minimum->hebergement_id,
                'month' => $request->minimum->month,
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

        $minimum = DB::table('minimums')
        ->select(
            'id',
            'hebergement_id',
            'minimum',
            'month',
        )
        ->where('hebergement_id', $id)
        ->get();

        return response()->json([
            'message' => 'OK',
            'prices' => $prices,
            'minimum' => $minimum
        ], 200);
    }

    public function modify_prices(Request $req)
    {

        foreach ($req->prices as $price) {
            $reduction = isset($price['reduction']) && $price['reduction'] !== '' ? $price['reduction'] : 0;
            if (!is_null($price['price']) && $price['price'] !== '') {
                Price::where('id', $price['id'])->update([
                    'price' => $price['price'],
                    'reduction' => $reduction,
                ]);
            }

        }

        if (isset($req->minimum) && !is_null($req->minimum) && $req->minimum !== '') {
            Minimum::where('hebergement_id', $req->minimum->hebergement_id)
            ->where('month', $req->minimum->month)
            ->update([
                'minimum' => $req->minimum->min,
                'hebergement_id' => $req->minimum->hebergement_id,
                'month' => $req->minimum->month,
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
