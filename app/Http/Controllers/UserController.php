<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function get_all_clients()
    {
        $plannings = DB::table('plannings')
            ->select(
                'status',
                'user_id',
            )
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
                'city'
            )
            ->get();


        foreach ($users as $user) {
            $user->status = "Pas de planning en cours";

            foreach ($plannings as $planning) {
                $user_id = $planning->user_id;

                if ($user_id == $user->id) {
                    if ($planning->status == 'En cours') {
                        $user->status = $planning->status;
                    }
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'clients' => $users
        ], 200);
    }

    public function get_client($id)
    {

        $user = DB::table('users')
            ->select(
                'id',
                'name',
                'address',
                'city',
                'phone',
                'email',
                'role'

            )
            ->where('id', $id)
            ->get();

        return response()->json([
            'message' => 'OK',
            'client' => $user
        ], 200);
    }

    public function delete_client($id)
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'user' => $user
        ], 200);
    }

    public function modify_client(Request $req)
    {
        $user = User::where('id', $req->id)->update(
            [
                'name' => $req->name,
                'city' => $req->city,
                'email' => $req->email,
                'address' => $req->address,
                'phone' => $req->phone,
                'role' => $req->role,
            ]
        );

        return response()->json([
            'message' => 'OK',
            'user' => $user
        ], 200);
    }

    public function create_client(Request $req)
    {
        $user = User::create([
            'name' => $req->name,
            'city' => $req->city,
            'email' => $req->email,
            'address' => $req->address,
            'phone' => $req->phone,
            'role' => $req->role,
            'password' => $req->password
        ]);

        return response()->json([
            'message' => 'OK',
            'user' => $user
        ], 200);
    }
}
