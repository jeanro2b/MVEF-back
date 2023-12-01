<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\StripeClient;

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
        $firstName = $requete->firstName;
        $phone = $requete->phone;
        $mail = $requete->mail;
        $amount = $requete->amount;

        $stripe = new StripeClient(config('services.stripe.secret_key'));

        try {
            $intent = $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'eur',
                //obso
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            Reservation::create([
                'name' => $requete->name,
                'long_title' => $requete->longTitle,
                'city' => $requete->city,
                'description' => str_replace("\n", '<br />', $requete->description),
                'pImage' => $pImage = '1' ? '' : $pImage,
                'sImage' => $sImage = '1' ? '' : $sImage,
                'tImage' => $tImage = '1' ? '' : $tImage,
                'type_id' => $requete->type,
                'code' => $requete->code,
                'destination_id' => $requete->destination_id,
                'price' => $int_price,
                'couchage' => $requete->couchage
            ]);

            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Gérez les erreurs Stripe ici
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
