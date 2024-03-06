<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'destination_id' => $request->destination == 0 ? null : $request->destination,
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
                'role',
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

            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                )
                ->where('id', $code->destination_id)
                ->get();

            foreach ($destination as $dest) {
                if ($dest && $dest->name) {
                    $code->destination_name = $dest->name;
                } else {
                    $code->destination_name = 'Toutes destinations';
                }

            }
        }

        return response()->json([
            'message' => 'OK',
            'codes' => $codes
        ], 200);
    }

    public function check_code($code, $destination)
    {
        $codes = Code::all()->where('code', $code);

        foreach ($codes as $code) {
            $dateIsValid = Carbon::parse($code->end)->isFuture();
            $destinationIsValid = $code->destination_id == $destination || $code->destination_id == null;
            $code->validity = $dateIsValid && $destinationIsValid;
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
                'destination_id' => $req->destination == 0 ? null : $req->destination,
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
                'end',
                'reduction',
                'destination_id',
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

            if ($code->destination_id == null) {
                $code->destination_id = 0;
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
}
