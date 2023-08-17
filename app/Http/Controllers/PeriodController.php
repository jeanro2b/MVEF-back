<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Http\Controllers\Controller;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ModifyPeriodEmail;
use App\Mail\ReservationMail;
use App\Models\Destination;
use App\Models\Hebergement;
use App\Models\Planning;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Response;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as PdfDompdf;

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

        foreach ($periods as $period) {
            $formattedDateStart = Carbon::parse($period->start)->format('d/m/Y');
            $formattedDateEnd = Carbon::parse($period->end)->format('d/m/Y');

            $period->start = $formattedDateStart;
            $period->end = $formattedDateEnd;
        }

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

    public function modify_planning_period(Request $req, $id)
    {

        $periods = $req->modify;

        $planningInfo = Planning::find($id);

        $hebergementInfo = Hebergement::find($planningInfo->hebergement_id);

        $destinationInfo = Destination::find($hebergementInfo->destination_id);

        $clientInfo = User::find($planningInfo->user_id);

        $hebergementCode = $hebergementInfo->code;
        $hebergementName = $hebergementInfo->name;
        $hebergementTitle = $hebergementInfo->long_title;
        $clientMail = $clientInfo->email;
        $clientName = $clientInfo->name;
        $libellePlanning = $planningInfo->object;
        $destinationName = $destinationInfo->name;

        $baseArray = (array) $req->base;
        $modifyArray = (array) $req->modify;

        foreach ($periods as $period) {
            Period::where('id', $period['id'])->update(
                [
                    'name' => $period['name'],
                    'phone' => $period['phone'],
                    'mail' => $period['mail'],
                    'number' => $period['number'],
                ]
            );
        }
        $dateDuJour = Carbon::now(); // Obtenir la date et l'heure actuelles

        $formatDate = $dateDuJour->format('Y-m-d'); // Format YYYY-MM-DD

        $logoPath = "https://mvef.s3.eu-west-3.amazonaws.com/base_logo_transparent_background.png";
        $logoData = base64_encode(file_get_contents($logoPath));

        $dompdf = new Dompdf();

        $html = View::make('pdf.modif_planning', compact('logoData', 'clientName', 'libellePlanning', 'destinationName', 'baseArray', 'modifyArray', 'hebergementCode', 'hebergementName', 'hebergementTitle', 'formatDate'))->render();

        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendu du PDF
        $dompdf->render();

        $output = $dompdf->output();

        $filename = "Modif_$destinationName-$hebergementName-$formatDate.pdf";

        // Envoi du PDF par e-mail avec pièce jointe
        $mailData = [
            'email' => 'admin@mesvacancesenfamille.com',
            'attachmentData' => $output,
            'attachmentName' => $filename
        ];

        Mail::to($mailData['email'])->send(new ModifyPeriodEmail($mailData, $clientName, $libellePlanning, $destinationName, $hebergementCode, $hebergementName, $hebergementTitle, $formatDate));

        return response()->json([
            'message' => 'OK',
            'planning' => $periods
        ], 200);
    }

    public function send_period(Request $req)
    {
        $periodInfo = Period::find($req->id);
        $planningInfo = Planning::find($periodInfo->planning_id);
        $hebergementInfo = Hebergement::find($planningInfo->hebergement_id);
        $destinationInfo = Destination::find($hebergementInfo->destination_id);
        $clientInfo = User::find($planningInfo->user_id);

        $services = Service::where('destination_id', $destinationInfo->id)->get();

        $nomVoyageur = $req->name;
        $libellePlanning = $planningInfo->object;
        $nomClient = $clientInfo->name;
        $nomDestination = $destinationInfo->name;
        $heureArrive = $destinationInfo->arrival;
        $heureDepart = $destinationInfo->departure;
        $descriptionHebergement = $hebergementInfo->description;
        $dateArrive = Carbon::createFromFormat('Y-m-d', $periodInfo->start)->format('d/m/Y');
        $dateDepart = Carbon::createFromFormat('Y-m-d', $periodInfo->end)->format('d/m/Y');
        $addressBetter = str_replace("<br />", "", $destinationInfo->address);
        $mail = $destinationInfo->mail;
        $phone = $destinationInfo->phone;
        $latitude = $destinationInfo->latitude;
        $longitude = $destinationInfo->longitude;
        $renseignement = $destinationInfo->renseignement;

        $logoPath = "https://mvef.s3.eu-west-3.amazonaws.com/base_logo_transparent_background.png";
        $logoData = base64_encode(file_get_contents($logoPath));

        $destPath = "https://mvef.s3.eu-west-3.amazonaws.com/icone-de-localisation-noire.png";
        $destData = base64_encode(file_get_contents($destPath));

        $calPath = "https://mvef.s3.eu-west-3.amazonaws.com/2370264.png";
        $calData = base64_encode(file_get_contents($calPath));

        $dompdf = new Dompdf();

        $html = View::make('pdf.bon_sejour', compact('nomClient', 'services', 'libellePlanning', 'nomClient', 'nomDestination', 'heureArrive', 'heureDepart', 'descriptionHebergement', 'dateArrive', 'dateDepart', 'addressBetter', 'mail', 'phone', 'latitude', 'longitude', 'logoData', 'destData', 'calData', 'nomVoyageur', 'renseignement'))->render();

        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendu du PDF
        $dompdf->render();

        $output = $dompdf->output();

        $filename = "PDF_bon_sejour_$nomVoyageur.pdf";

        // Envoi du PDF par e-mail avec pièce jointe
        $mailData = [
            'email' => $req->mail,
            'attachmentData' => $output,
            'attachmentName' => $filename
        ];

        Mail::to($mailData['email'])->send(new ReservationMail($mailData));

        return 'Le PDF a été généré et envoyé par e-mail.';
    }

    public function download_pdf(Request $req)
    {
        $periodInfo = Period::find($req->id);
        $planningInfo = Planning::find($periodInfo->planning_id);
        $hebergementInfo = Hebergement::find($planningInfo->hebergement_id);
        $destinationInfo = Destination::find($hebergementInfo->destination_id);
        $clientInfo = User::find($planningInfo->user_id);

        $services = Service::where('destination_id', $destinationInfo->id)->get();

        $nomVoyageur = $req->name;
        $libellePlanning = $planningInfo->object;
        $nomClient = $clientInfo->name;
        $nomDestination = $destinationInfo->name;
        $heureArrive = $destinationInfo->arrival;
        $heureDepart = $destinationInfo->departure;
        $descriptionHebergement = $hebergementInfo->description;
        $dateArrive = Carbon::createFromFormat('Y-m-d', $periodInfo->start)->format('d/m/Y');
        $dateDepart = Carbon::createFromFormat('Y-m-d', $periodInfo->end)->format('d/m/Y');
        $addressBetter = str_replace("<br />", "", $destinationInfo->address);
        $mail = $destinationInfo->mail;
        $phone = $destinationInfo->phone;
        $latitude = $destinationInfo->latitude;
        $longitude = $destinationInfo->longitude;

        $logoPath = "https://mvef.s3.eu-west-3.amazonaws.com/base_logo_transparent_background.png";
        $logoData = base64_encode(file_get_contents($logoPath));

        $destPath = "https://mvef.s3.eu-west-3.amazonaws.com/icone-de-localisation-noire.png";
        $destData = base64_encode(file_get_contents($destPath));

        $calPath = "https://mvef.s3.eu-west-3.amazonaws.com/2370264.png";
        $calData = base64_encode(file_get_contents($calPath));

        $dompdf = new Dompdf();

        $html = View::make('pdf.bon_sejour', compact('nomClient', 'services', 'libellePlanning', 'nomClient', 'nomDestination', 'heureArrive', 'heureDepart', 'descriptionHebergement', 'dateArrive', 'dateDepart', 'addressBetter', 'mail', 'phone', 'latitude', 'longitude', 'logoData', 'destData', 'calData', 'nomVoyageur'))->render();

        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendu du PDF
        $dompdf->render();

        $output = $dompdf->output();

        $filename = "PDF_bon_sejour_$nomVoyageur.pdf";

        $contentType = 'application/pdf';

        // Création de la réponse HTTP avec le contenu du PDF
        $response = new Response($output, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

        return $response;
    }

    public function admin_modify_planning_period(Request $req)
    {

        $start = Carbon::createFromFormat('d/m/Y', $req->input('start'));
        $end = Carbon::createFromFormat('d/m/Y', $req->input('end'));
        $name = $req->input('name');
        $phone = $req->input('phone');
        $mail = $req->input('mail');
        $number = $req->input('number');

        $period = Period::where('id', $req->id)->update(
            [
                'start' => $start,
                'end' => $end,
                'mail' => $mail,
                'phone' => $phone,
                'name' => $name,
                'number' => $number,
            ]
        );

        return response()->json([
            'message' => 'OK',
            'period' => $period
        ], 200);
    }
}
