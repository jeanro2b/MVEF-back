<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{

    public function create_code(Request $request)
    {
        $code = Code::create([
            'code' => $request->name,
            'end' => $request->end->toDateTimeString(),
            'user_id' => $request->user,
        ]);

        return response()->json([
            'message' => 'OK',
            'code' => $code
        ], 200);
    }

    public function get_all_codes()
    {
        $codes = DB::table('codes')
        ->select(
            'id',
            'code',
            'user_id',
            'end'
        )
        ->get();

        return response()->json([
            'message' => 'OK',
            'codes' => $codes
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function delete_code($id)
    {
        $code = DB::table('codes')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'code' => $code
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
