<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CodeController extends Controller
{

    public function create_code(Request $request)
    {
        $code = Code::create([
            'code' => $request->code,
            'end' => $request->end,
            'user_id' => $request->user,
            'reduction' => $request->reduction,
        ]);

        return response()->json([
            'message' => 'OK',
            'code' => $code
        ], 200);
    }

    public function get_all_codes()
    {
        $codes = Code::all();

        $users = DB::table('users')
        ->select(
            'id',
            'name',
            'city',
            'role'
        )
        ->get();

        foreach ($codes as $code) {
            $formattedDateEnd = Carbon::parse($code->end)->format('d/m/Y');
            $code->end = $formattedDateEnd;
            foreach ($users as $user) {
                if ($user->id == $code->user_id) {
                    $code->user_name = $user->name;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'codes' => $codes
        ], 200);
    }

    public function modify_code(Request $req)
    {

        $code = Code::where('id', $req->id)->update(
            [
                'code' => $req->code,
                'end' => $req->end,
                'user_id' => $req->user,
                'reduction' => $req->reduction,
            ]
        );
        

        return response()->json([
            'message' => 'OK',
            'code' => $code
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

    public function get_code($id)
    {

        $codes = DB::table('codes')
            ->select(
                'id',
                'code',
                'user_id',
                'end'
            )
            ->where('id', $id)
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
                'city',
                'role'
            )
            ->get();

        foreach ($codes as $code) {
            $formattedDateEnd = Carbon::parse($code->end)->format('d/m/Y');
            $code->end = $formattedDateEnd;
            foreach ($users as $user) {
                if ($user->id == $code->user_id) {
                   $code->user_name = $user->name;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'code' => $codes
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
