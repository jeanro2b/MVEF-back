<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{

    public function get_planning_periods($id)
    {

        $periods = DB::table('periods')
            ->select(
                'id',
                'start',
                'end',
                'name',
                'phone',
                'mail',
                'number'
            )
            ->where('planning_id', $id)
            ->get();

        return response()->json([
            'message' => 'OK',
            'periods' => $periods
        ], 200);
    }

    public function get_planning_periods_all()
    {

        $periods = DB::table('periods')
            ->select(
                'id',
                'start',
                'end',
                'name',
                'phone',
                'mail',
                'number'
            )
            ->get();

        return response()->json([
            'message' => 'OK',
            'periods' => $periods
        ], 200);
    }


    public function delete_planning_period($id)
    {
        $period = DB::table('periods')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'period' => $period
        ], 200);
    }

    public function create_planning_period(Request $req, $id)
    {

        $period = Period::create([
            'start' => $req->start,
            'end' => $req->end,
            'name' => $req->name,
            'phone' => $req->phone,
            'mail' => $req->mail,
            'number' => $req->number,
            'planning_id' => $id,

        ]);

        return response()->json([
            'message' => 'OK',
            'period' => $period
        ], 200);
    }

    public function modify_planning_period(Request $req)
    {

        $period = Period::where('id', $req->id)->update(
            [
                'name' => $req->name,
                'phone' => $req->phone,
                'mail' => $req->mail,
                'number' => $req->number,
            ]
        );

        return response()->json([
            'message' => 'OK',
            'period' => $period
        ], 200);
    }
}
