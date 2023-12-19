<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Mail\LocationDemandEmail;
use App\Mail\LocationDemandEmailUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;



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
    public function create_payment_intent(Request $requete)
    {
        $name = $requete['name'];
        $first_name = $requete['firstName'];
        $phone = $requete['phone'];
        $mail = $requete['mail'];
        $amount = $requete['amount'];
        $services = $requete['services'];
        $voyageurs = $requete['voyageurs'];

        $start = Carbon::parse($requete['start']);
        $end = Carbon::parse($requete['end']);
        // $start = Carbon::createFromFormat('D M d Y H:i:s eO', $requete['start']);
        // $end = Carbon::createFromFormat('D M d Y H:i:s eO', $requete['end']);
        $startDate = $start->toDateTimeString();
        $endDate = $end->toDateTimeString();
        $yearStart = $start->year;
        $monthStart = $start->month + 1;
        $dayStart = $start->day + 1;
        $yearEnd = $end->year;
        $monthEnd = $end->month + 1;
        $dayEnd = $end->day + 1;

        $destination_id = $requete['destination_id'];
        $hebergement_id = $requete['hebergement_id'];
        $user_id = $requete['userId'];
        $ownerEmail = 'jrgabet@hotmail.fr';
        $hebergementName = 'Nom';

        $users = User::where('id', $user_id)->get();
        foreach ($users as $user) {
            $user_email = $user->email;
        }


        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        try {
            $intent = $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'eur',
                //obso
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            $token = Str::random(21);

            $reservation = Reservation::create([
                'name' => $name,
                'first_name' => $first_name,
                'phone' => $phone,
                'mail' => $mail,
                'destination_id' => $destination_id,
                'hebergement_id' => $hebergement_id,
                'user_id' => $user_id,
                'amount' => $amount,
                'start' => $startDate,
                'end' => $endDate,
                'intent' => $intent->id,
                'status' => 'A venir',
                'token' => $token,
                'services' => json_encode($services),
                'voyageurs' => $voyageurs,
            ]);

            $reservationId = $reservation->id;

            Mail::to($user_email)->send(new LocationDemandEmailUser($reservationId, $hebergementName, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $destination_id));

            Mail::to($ownerEmail)->send(new LocationDemandEmail($token, $reservationId, $hebergementName, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $destination_id));

            return response()->json([
                'message' => 'OK',
            ], 200);
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
            'created_at',
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
            'voyageurs',
            'hebergement_id',
            'user_id'
        )
        ->get();

        foreach($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                )
                ->where('id', $reservation->destination_id)
                ->get();
            
            $reservation->destination_name = $destination->name;

            $hebergement = DB::table('hebergements')
                ->select(
                    'id',
                    'name',
                    'code'
                )
                ->where('id', $reservation->hebergement_id)
                ->get();

            $reservation->code = $hebergement->code;
        }




        return response()->json([
            'message' => 'OK',
            'reservations' => $reservations,
        ], 200);
    }

    public function accept_reservation(Request $requete)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $user_id = $requete['user'];
        $token = $requete['secret'];

        $users = User::where('id', $user_id)->get();
        foreach ($users as $user) {
            $user_email = $user->email;
        }

        $reservations = Reservation::where('token', $token)->get();
        foreach ($reservations as $reservation) {
            $payment_intent = $reservation->intent;
            $start = $reservation->start;
            $end = $reservation->end;
            $hebergement_id = $reservation->hebergement_id;
            $destination_id = $reservation->destination_id;
            $amount = $reservation->amount;
            $services = $reservation->services;
            $name = $reservation->name;
            $first_name = $reservation->first_name;
        }
        $ownerEmail = 'jrgabet@hotmail.fr';
        
        if($reservation) {

            $stripe->paymentIntents->confirm(
                $payment_intent,
                [
                'payment_method' => 'pm_card_visa',
                'return_url' => 'https://www.mesvacancesenfamille.com'
                ]
            );

            // Mail::to($user_email)->send(new LocationAcceptedEmailUser());
            // Mail::to($ownerEmail)->send(new LocationAcceptedEmail());

            return response()->json([
                'message' => 'OK',
            ], 200);
        } else {
            return response()->json([
                'message' => 'KO',
            ], 400);
        }
    }

    public function refuse_reservation(Request $requete)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $user_id = $requete['user'];
        $token = $requete['secret'];
        Log::debug($token);

        $reservation = Reservation::where('token', $token)->get();
        Log::debug($reservation->intent);
        

        if($reservation) {
            // Envoyer mail au client
            // Mettre le statut en refusé
            return response()->json([
                'message' => 'OK',
            ], 200);
        } else {
            return response()->json([
                'message' => 'KO',
            ], 400);
        }
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
