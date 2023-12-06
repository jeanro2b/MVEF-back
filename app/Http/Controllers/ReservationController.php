<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;


class ReservationController extends Controller
{
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
    public function create(Request $req)
    {
        $requete = json_decode($req);
        $name = $requete->name;
        $first_name = $requete->firstName;
        $phone = $requete->phone;
        $mail = $requete->mail;
        $amount = $requete->amount;
        $start = $requete->start;
        $end = $requete->end;
        $destination_id = $requete->destination_id;
        $hebergement_id = $requete->hebergement_id;
        $user_id = $requete->user_id;
        //status
        Log::debug($name);
        Log::debug($first_name);
        Log::debug($phone);
        Log::debug($mail);
        Log::debug($amount);
        Log::debug($start);
        Log::debug($end);
        Log::debug($destination_id);
        Log::debug($hebergement_id);
        Log::debug($user_id);

        $stripe = new StripeClient(config('services.stripe.secret_key'));

        try {
            $intent = $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'eur',
                //obso
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            Reservation::create([
                'name' => $name,
                'first_name' => $first_name,
                'phone' => $phone,
                'mail' => $mail,
                'destination_id' => $destination_id,
                'hebergement_id' => $hebergement_id,
                'user_id' => $user_id,
                'amount' => $amount,
                'start' => $start,
                'end' => $end,
                'status' => 'A venir'
            ]);

            Log::info('ici');

            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Gérez les erreurs Stripe ici
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function get_reservation($id)
    {
        $reservation = Reservation::where('id', $id)->get();

        return response()->json([
            'message' => 'OK',
            'reservation' => $reservation,
        ], 200);
    }

    public function get_reservation_user($id)
    {
        $reservation = Reservation::where('user_id', $id)->get();

        return response()->json([
            'message' => 'OK',
            'reservation' => $reservation,
        ], 200);
    }

    public function get_all_reservations()
    {
        $reservations = DB::table('reservations')
        ->select(
            'id',
            'text',
            'destination_id',
            'start',
            'end',
            'status',
            'amount',
            'intent',
            'name',
            'first_name',
            'phone',
            'mail',
            'hebergement_id',
            'user_id'
        )
        ->get();


        return response()->json([
            'message' => 'OK',
            'reservations' => $reservations,
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
