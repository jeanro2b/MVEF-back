<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Destination;
use App\Models\Hebergement;
use App\Models\Service;
use App\Mail\LocationDemandEmail;
use App\Mail\LocationDemandEmailUser;
use App\Mail\LocationAcceptedEmailUser;
use App\Mail\LocationAcceptedEmail;
use App\Mail\LocationRefusedEmailUser;
use App\Mail\LocationDeleteEmailUser;
use App\Mail\LocationDeleteEmail;
use App\Mail\LocationDeleteEmailAdmin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
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
        $nights = $requete['nights'];
        $amount_options = $amount - $nights;

        $start = Carbon::parse($requete['start']);
        $end = Carbon::parse($requete['end']);
        // $start = Carbon::createFromFormat('D M d Y H:i:s eO', $requete['start']);
        // $end = Carbon::createFromFormat('D M d Y H:i:s eO', $requete['end']);
        $startDate = $start->toDateTimeString();
        $endDate = $end->toDateTimeString();
        $yearStart = $start->year;
        $monthStart = sprintf('%02d', $start->month);
        $dayStart = sprintf('%02d', $start->day);
        $yearEnd = $end->year;
        $monthEnd = sprintf('%02d', $end->month);
        $dayEnd = sprintf('%02d', $end->day);

        $destination_id = $requete['destination_id'];
        $hebergement_id = $requete['hebergement_id'];
        $user_id = $requete['userId'];

        $destinations = Destination::where('id', $destination_id)->get();
        $hebergements = Hebergement::where('id', $hebergement_id)->get();

        foreach ($destinations as $destination) {
            $ownerEmail = $destination->mail;
        }

        foreach ($hebergements as $hebergement) {
            $hebergementName = $hebergement->long_title;
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
                'amount_options' => $amount_options,
            ]);

            $reservationId = $reservation->id;

            Mail::to($mail)->send(new LocationDemandEmailUser($reservationId, $hebergementName, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $destination_id, $amount));

            Mail::to($ownerEmail)->send(new LocationDemandEmail($token, $reservationId, $hebergementName, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $destination_id, $name, $first_name, $user_id));

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
        $reservations = Reservation::where('id', $id)->get();

        return response()->json([
            'message' => 'OK',
            'reservation' => $reservations,
        ], 200);
    }

    public function get_reservation_user($id)
    {
        $reservations = Reservation::where('user_id', $id)->get();

        foreach ($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                    'site'
                )
                ->where('id', $reservation->destination_id)
                ->get();

            foreach ($destination as $dest) {
                $reservation->destination_name = $dest->name;

                $formattedDateStart = Carbon::parse($reservation->start)->format('d/m/Y');
                $formattedDateEnd = Carbon::parse($reservation->end)->format('d/m/Y');
                $formattedAmount = number_format($reservation->amount, 0, '.', '') . ' €';

                $reservation->start = $formattedDateStart;
                $reservation->end = $formattedDateEnd;
                $reservation->amount = $formattedAmount;
                $reservation->site = $dest->site;

                $hebergement = DB::table('hebergements')
                    ->select(
                        'id',
                        'long_title',
                        'couchage'
                    )
                    ->where('id', $reservation->hebergement_id)
                    ->get();

                foreach ($hebergement as $heb) {
                    $reservation->hebergement_name = $heb->long_title;
                    $reservation->couchage = $heb->couchage;
                }
            }
        }

        return response()->json([
            'message' => 'OK',
            'reservation' => $reservations,
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

        foreach ($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                )
                ->where('id', $reservation->destination_id)
                ->get();

            foreach ($destination as $dest) {
                $reservation->destination_name = $dest->name;
            }

            $hebergement = DB::table('hebergements')
                ->select(
                    'id',
                    'name',
                    'code'
                )
                ->where('id', $reservation->hebergement_id)
                ->get();

            foreach ($hebergement as $heb) {
                $reservation->code = $heb->code;
            }
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

        $reservations = Reservation::where('token', $token)->get();
        foreach ($reservations as $reservation) {
            $payment_intent = $reservation->intent;
            $start = Carbon::createFromFormat('Y-m-d', $reservation->start);
            $end = Carbon::createFromFormat('Y-m-d', $reservation->end);
            $hebergement_id = $reservation->hebergement_id;
            $destination_id = $reservation->destination_id;
            $amount = $reservation->amount;
            $services = $reservation->services;
            $name = $reservation->name;
            $first_name = $reservation->first_name;
            $reservationId = $reservation->id;
            $phone = $reservation->phone;
            $user_email = $reservation->mail;
            $user_name = $reservation->name;
            $user_firstname = $reservation->first_name;
        }
        $destinations = Destination::where('id', $destination_id)->get();
        $hebergements = Hebergement::where('id', $hebergement_id)->get();

        foreach ($destinations as $destination) {
            $destinationName = $destination->name;
            $ownerEmail = $destination->mail;
        }

        foreach ($hebergements as $hebergement) {
            $hebergementName = $hebergement->long_title;
        }

        $yearStart = $start->year;
        $monthStart = sprintf('%02d', $start->month);
        $dayStart = sprintf('%02d', $start->day + 1);
        $yearEnd = $end->year;
        $monthEnd = sprintf('%02d', $end->month);
        $dayEnd = sprintf('%02d', $end->day + 1);

        if ($reservation) {

            $stripe->paymentIntents->confirm(
                $payment_intent,
                [
                    'payment_method' => 'pm_card_visa',
                    'return_url' => 'https://www.mesvacancesenfamille.com'
                ]
            );

            $hebergementInfo = Hebergement::find($hebergement_id);
            $destinationInfo = Destination::find($destination_id);

            $services = Service::where('destination_id', $destination_id)->get();

            $nomVoyageur = $user_name;
            $libellePlanning = "Test";
            $nomClient = $user_name;
            $nomDestination = $destinationInfo->name;
            $heureArrive = $destinationInfo->arrival;
            $heureDepart = $destinationInfo->departure;
            $descriptionHebergement = $hebergementInfo->description;
            $nomHebergement = $hebergementInfo->long_title;
            $dateArrive = $start->format('d/m/Y');
            $dateDepart = $end->format('d/m/Y');
            $addressBetter = str_replace("<br />", "", $destinationInfo->address);
            $mail = $destinationInfo->mail;
            $phone = $destinationInfo->phone;
            $latitude = $destinationInfo->latitude;
            $longitude = $destinationInfo->longitude;
            $renseignement = $destinationInfo->renseignement;
            $villeDestination = $destinationInfo->city;
            $caution = $destinationInfo->caution;
            $taxe = $destinationInfo->taxe;

            $logoPath = "https://mvef.s3.eu-west-3.amazonaws.com/base_logo_transparent_background.png";
            $logoData = base64_encode(file_get_contents($logoPath));
            $bslogoPath = "https://mvef.s3.eu-west-3.amazonaws.com/bslogo.png";
            $bslogoData = base64_encode(file_get_contents($bslogoPath));
            $bslogotxtPath = "https://mvef.s3.eu-west-3.amazonaws.com/bslogotxt.png";
            $bslogotxtData = base64_encode(file_get_contents($bslogotxtPath));

            $destPath = "https://mvef.s3.eu-west-3.amazonaws.com/icone-de-localisation-noire.png";
            $destData = base64_encode(file_get_contents($destPath));

            $calPath = "https://mvef.s3.eu-west-3.amazonaws.com/2370264.png";
            $calData = base64_encode(file_get_contents($calPath));

            $hebPath = "https://mvef.s3.eu-west-3.amazonaws.com/588a6695d06f6719692a2d1c.png";
            $hebData = base64_encode(file_get_contents($hebPath));

            $logoVacancesAuthPath = "https://mvef.s3.eu-west-3.amazonaws.com/LOGO-VACANCES+AUTHENTIQUES.jpg";
            $logoVacancesAuthData = base64_encode(file_get_contents($logoVacancesAuthPath));

            $dompdf = new Dompdf();

            $html = View::make('pdf.bon_sejour', compact('nomHebergement', 'services', 'libellePlanning', 'nomClient', 'nomDestination', 'heureArrive', 'heureDepart', 'descriptionHebergement', 'dateArrive', 'dateDepart', 'addressBetter', 'mail', 'phone', 'latitude', 'longitude', 'logoData', 'destData', 'calData', 'hebData', 'logoVacancesAuthData', 'nomVoyageur', 'renseignement', 'villeDestination', 'caution', 'taxe', 'bslogoData', 'bslogotxtData'))->render();

            // Chargement du contenu HTML dans Dompdf
            $dompdf->loadHtml($html);

            // Rendu du PDF
            $dompdf->render();

            $output = $dompdf->output();

            $filename = "PDF_bon_sejour_$name.pdf";

            Mail::to($user_email)->send(new LocationAcceptedEmailUser($destination_id, $destinationName, $reservationId, $amount, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $output, $filename));
            Mail::to($ownerEmail)->send(new LocationAcceptedEmail($hebergementName, $reservationId, $amount, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd, $name, $first_name, $phone, $output, $filename));


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

        $reservations = Reservation::where('token', $token)->get();

        foreach ($reservations as $reservation) {
            $payment_intent = $reservation->intent;
            $start = Carbon::createFromFormat('Y-m-d', $reservation->start);
            $end = Carbon::createFromFormat('Y-m-d', $reservation->end);
            $hebergement_id = $reservation->hebergement_id;
            $destination_id = $reservation->destination_id;
            $amount = $reservation->amount;
            $services = $reservation->services;
            $name = $reservation->name;
            $first_name = $reservation->first_name;
            $reservationId = $reservation->id;
            $phone = $reservation->phone;
            $user_email = $reservation->mail;
            $user_name = $reservation->name;
            $user_firstname = $reservation->first_name;
        }
        $destinations = Destination::where('id', $destination_id)->get();
        $hebergements = Hebergement::where('id', $hebergement_id)->get();

        foreach ($destinations as $destination) {
            $destinationName = $destination->name;
        }

        foreach ($hebergements as $hebergement) {
            $hebergementName = $hebergement->name;
        }

        $yearStart = $start->year;
        $monthStart = sprintf('%02d', $start->month);
        $dayStart = sprintf('%02d', $start->day + 1);
        $yearEnd = $end->year;
        $monthEnd = sprintf('%02d', $end->month);
        $dayEnd = sprintf('%02d', $end->day + 1);

        if ($reservation) {
            $stripe->paymentIntents->cancel(
                $payment_intent,
                []
            );

            Mail::to($user_email)->send(new LocationRefusedEmailUser($destination_id, $destinationName, $reservationId, $amount, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd));
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

    public function message_reservation(Request $requete)
    {
        $secret_id = $requete['secretId'];
        $message = $requete['message'];

        $reservation = Reservation::where('token', $secret_id)->update(
            [
                'comment' => $message,
            ]
        );

        return response()->json([
            'message' => 'OK',
        ], 200);

    }

    public function delete_reservation($id)
    {

        $reservations = Reservation::where('id', $id)->get();
        // Add mails

        foreach ($reservations as $reservation) {
            $start = Carbon::createFromFormat('Y-m-d', $reservation->start);
            $end = Carbon::createFromFormat('Y-m-d', $reservation->end);
            $hebergement_id = $reservation->hebergement_id;
            $destination_id = $reservation->destination_id;
            $name = $reservation->name;
            $first_name = $reservation->first_name;
            $reservationId = $reservation->id;
            $user_email = $reservation->mail;
        }

        $destinations = Destination::where('id', $destination_id)->get();
        $hebergements = Hebergement::where('id', $hebergement_id)->get();

        foreach ($destinations as $destination) {
            $destinationMail = $destination->mail;
        }

        foreach ($hebergements as $hebergement) {
            $hebergementName = $hebergement->long_title;
        }

        $yearStart = $start->year;
        $monthStart = sprintf('%02d', $start->month);
        $dayStart = sprintf('%02d', $start->day + 1);
        $yearEnd = $end->year;
        $monthEnd = sprintf('%02d', $end->month);
        $dayEnd = sprintf('%02d', $end->day + 1);
        $admin_mail = 'admin@mesvacancesenfamille.com';

        Reservation::where('id', $id)->delete();

        Mail::to($admin_mail)->send(new LocationDeleteEmailAdmin($hebergementName, $name, $first_name, $reservationId, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd));
        Mail::to($destinationMail)->send(new LocationDeleteEmail($hebergementName, $name, $first_name, $reservationId, $yearStart, $monthStart, $dayStart, $yearEnd, $monthEnd, $dayEnd));
        Mail::to($user_email)->send(new LocationDeleteEmailUser($reservationId));

        return response()->json([
            'message' => 'OK',
            'reservation' => $reservation
        ], 200);
    }

    public function get_all_reservations_for_facturation()
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
                'user_id',
                'amount_options'
            )
            ->get();

        foreach ($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                    'tva',
                    'tva_options'
                )
                ->where('id', $reservation->destination_id)
                ->get();

            foreach ($destination as $dest) {
                $reservation->destination_name = $dest->name;
                $reservation->tva = ($dest->tva) / 100;
                $reservation->tva_options = $dest->tva_options;
            }

            $hebergement = DB::table('hebergements')
                ->select(
                    'id',
                    'name',
                    'code'
                )
                ->where('id', $reservation->hebergement_id)
                ->get();

            foreach ($hebergement as $heb) {
                $reservation->code = $heb->code;
            }

            $reservation->amountHT = $reservation->amount / (1 + $reservation->tva);
            $reservation->amountTVA = $reservation->amount - $reservation->amountHT;

            $reservation->amountHTOptions = $reservation->amount_options / (1 + $reservation->tva_options);
            $reservation->amountTVAOptions = $reservation->amount_options - $reservation->amountHTOptions;
        }

        return response()->json([
            'message' => 'OK',
            'reservations' => $reservations,
        ], 200);
    }

    public function download_facturation_reservation($id)
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
                'user_id',
                'amount_options',
                'services'
            )
            ->where('id', $id)
            ->get();

        foreach ($reservations as $reservation) {
            $destination = DB::table('destinations')
                ->select(
                    'id',
                    'name',
                    'tva',
                    'tva_options'
                )
                ->where('id', $reservation->destination_id)
                ->get();

            foreach ($destination as $dest) {
                $reservation->destination_name = $dest->name;
                $reservation->tva = ($dest->tva) / 100;
                $reservation->tva_options = $dest->tva_options;

                $reservationTVA = $reservation->tva;
                $reservationTVAOptions = $reservation->tva_options;
            }

            $hebergement = DB::table('hebergements')
                ->select(
                    'id',
                    'name',
                    'code',
                    'long_title'
                )
                ->where('id', $reservation->hebergement_id)
                ->get();

            foreach ($hebergement as $heb) {
                $reservation->code = $heb->code;

                $reservationHebergementTitle = $heb->long_title;
            }

            $reservation->amountHT = $reservation->amount / (1 + $reservation->tva);
            $reservation->amountTVA = $reservation->amount - $reservation->amountHT;

            $reservation->amountHTOptions = $reservation->amount_options / (1 + $reservation->tva_options);
            $reservation->amountTVAOptions = $reservation->amount_options - $reservation->amountHTOptions;


            $reservationId = $reservation->id;
            $reservationAmount = $reservation->amount;
            $reservationAmountOptions = $reservation->amount_options;
            $reservationAmountExclOptions = $reservationAmount - $reservationAmountOptions;
            $userId = $reservation->user_id;
            $reservationClientName = $reservation->name;
            $reservationClientFirstName = $reservation->first_name;
            $reservationClientPhone = $reservation->phone;
            $reservationClientMail = $reservation->mail;
            $reservationOptionsData = json_decode($reservation->services, true);
            $start = Carbon::createFromFormat('Y-m-d', $reservation->start);
            $end = Carbon::createFromFormat('Y-m-d', $reservation->end);
            $reservationNumberOfNights = $start->diffInDays($end);
            $reservationIntent = $reservation->intent;
        }

        $bslogoPath = "https://mvef.s3.eu-west-3.amazonaws.com/bslogo.png";
        $bslogoData = base64_encode(file_get_contents($bslogoPath));
        $bslogotxtPath = "https://mvef.s3.eu-west-3.amazonaws.com/bslogotxt.png";
        $bslogotxtData = base64_encode(file_get_contents($bslogotxtPath));

        $date = Carbon::now()->format('d/m/Y');

        $dompdf = new Dompdf();

        $reservationClientPhone = $reservation->phone;
        $html = View::make('pdf.facture_reservation', compact('reservationHebergementTitle', 'reservationId', 'reservationAmount', 'userId', 'reservationClientName', 'reservationClientFirstName', 'reservationClientPhone', 'reservationClientMail', 'reservationOptionsData', 'reservationNumberOfNights', 'date', 'reservationAmountOptions', 'reservationTVA', 'reservationTVAOptions', 'reservationIntent', 'bslogoData', 'bslogotxtData', 'reservationAmountExclOptions'))->render();

        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendu du PDF
        $dompdf->render();

        $output = $dompdf->output();

        $filename = "facture_$reservationId.pdf";

        $contentType = 'application/pdf';

        // Création de la réponse HTTP avec le contenu du PDF
        $response = new Response($output, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

        return $response;
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
