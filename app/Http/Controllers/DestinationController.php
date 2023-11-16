<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Equipements;
use App\Models\Hebergement;
use App\Models\Period;
use App\Models\Planning;
use App\Models\Retours;
use App\Models\Service;
use App\Models\Servicespayant;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class DestinationController extends Controller
{

    public function get_all_destinations(Request $req)
    {
        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'destination_id',
                'price',
                'type_id',
                'couchage'
            )
            ->get();


        $destinations = DB::table('destinations')
            ->select(
                'id',
                'name',
                'city',
                'favorite',
                'description',
                'pImage',
                'location',
            )
            ->get();

        $plannings = DB::table('plannings')
            ->select(
                'id',
                'hebergement_id',
            )
            ->get();

        $periods = DB::table('periods')
            ->select(
                'id',
                'start',
                'end',
                'planning_id'
            )
            ->get();

        foreach ($destinations as $destination) {
            $nombre_herbegements = 0;
            $min_price = 0;
            $appartement = false;
            $mobil_home = false;
            $villa = false;
            $chalet = false;
            $insolite = false;
            $types = [];
            $couchages = [];


            foreach ($hebergements as $hebergement) {
                if ($hebergement->destination_id == $destination->id) {
                    $nombre_herbegements++;
                    array_push($couchages, strval($hebergement->couchage));
                    if ($hebergement->type_id == 1) {
                        $appartement = true;
                    }
                    if ($hebergement->type_id == 2) {
                        $mobil_home = true;
                    }
                    if ($hebergement->type_id == 3) {
                        $villa = true;
                    }
                    if ($hebergement->type_id == 4) {
                        $chalet = true;
                    }
                    if ($hebergement->type_id == 5) {
                        $insolite = true;
                    }
                }
                if ($hebergement->destination_id == $destination->id && $hebergement->price > $min_price) {
                    $min_price = $hebergement->price;
                }
            }

            if ($appartement == true) {
                array_push($types, 'Appartement');
            }
            if ($mobil_home == true) {
                array_push($types, 'MobilHome');
            }
            if ($villa == true) {
                array_push($types, 'Villa');
            }
            if ($chalet == true) {
                array_push($types, 'Chalet');
            }
            if ($insolite == true) {
                array_push($types, 'Hébergement insolite');
            }

            $destination->min_price = $min_price;
            $destination->nombre = $nombre_herbegements;
            $destination->types = $types;
            $destination->couchage = $couchages;
        }

        if ($req->start_date) {
            //faire les filtres dates


            foreach ($destinations as $destination) {
                $dispo = true;
                foreach ($hebergements as $hebergement) {
                    if ($hebergement->destination_id == $destination->id) {
                        foreach ($plannings as $planning) {
                            if ($planning->hebergement_id == $hebergement->id) {
                                foreach ($periods as $period) {
                                    if ($period->start <= $req->start_date && $period->end >= $req->end_date) {
                                        $dispo = false;
                                    }
                                }
                            }
                        }
                    }
                }

                $destination->dispo = $dispo;
            }
        }


        return $destinations;
    }

    public function get_destination($id)
    {

        $hebergements = DB::table('hebergements')
            ->select(
                'id',
                'name',
                'price',
                'pImage',
                'sImage',
                'tImage',
                'description',
                'couchage',
                'long_title'
            )
            ->where('destination_id', $id)
            ->get();

        $equipements = DB::table('equipements')
            ->select(
                'id',
                'text',
                'hebergement_id'
            )
            ->get();

        $services = DB::table('services')
            ->select(
                'id',
                'destination_id',
                'text'
            )
            ->where('destination_id', $id)
            ->get();
        
        $servicespayants = DB::table('servicespayants')
            ->select(
                'id',
                'destination_id',
                'text'
            )
            ->where('destination_id', $id)
            ->get();

        $retours = DB::table('retours')
            ->select(
                'id',
                'destination_id',
                'text'
            )
            ->where('destination_id', $id)
            ->get();

        $destinations = DB::table('destinations')
            ->where('id', $id)
            ->get();

        foreach ($hebergements as $hebergement) {
            $equips = [];
            foreach ($equipements as $equipement) {
                if ($equipement->hebergement_id == $hebergement->id) {
                    array_push($equips, $equipement);
                }
            }
            $hebergement->equipements = $equips;

            if (!empty($hebergement->pImage)) {
                $hp_Image = Storage::disk('s3')->url($hebergement->pImage);
            } else {
                $hp_Image = '';
            }

            if (!empty($hebergement->sImage)) {
                $hs_Image = Storage::disk('s3')->url($hebergement->sImage);
            } else {
                $hs_Image = '';
            }

            if (!empty($hebergement->tImage)) {
                $ht_Image = Storage::disk('s3')->url($hebergement->tImage);
            } else {
                $ht_Image = '';
            }

            $hebergement->pImage = $hp_Image;
            $hebergement->sImage = $hs_Image;
            $hebergement->tImage = $ht_Image;
        }


        foreach ($destinations as $dest) {

            if (!empty($dest->pImage)) {
                $p_Image = Storage::disk('s3')->url($dest->pImage);
            }

            if (!empty($dest->sImage)) {
                $s_Image = Storage::disk('s3')->url($dest->sImage);
            }

            if (!empty($dest->tImage1)) {
                $t1_Image = Storage::disk('s3')->url($dest->tImage1);
            }

            if (!empty($dest->tImage2)) {
                $t2_Image = Storage::disk('s3')->url($dest->tImage2);
            }

            if (!empty($dest->tImage3)) {
                $t3_Image = Storage::disk('s3')->url($dest->tImage3);
            }

            if (!empty($dest->tImage4)) {
                $t4_Image = Storage::disk('s3')->url($dest->tImage4);
            }
        
        }

        if (isset($p_Image) && isset($s_Image) && isset($t1_Image) && isset($t2_Image) && isset($t3_Image) && isset($t4_Image)) {
            return response()->json([
                'message' => 'OK',
                'destinations' => $destinations,
                'hebergements' => $hebergements,
                'services' => $services,
                'servicespayants' => $servicespayants,
                'retours' => $retours,
                'pImage' => isset($p_Image) ? $p_Image : '',
                'sImage' => isset($s_Image) ? $s_Image : '',
                'tImage1' => isset($t1_Image) ? $t1_Image : '',
                'tImage2' => isset($t2_Image) ? $t2_Image : '',
                'tImage3' => isset($t3_Image) ? $t3_Image : '',
                'tImage4' => isset($t4_Image) ? $t4_Image : '',
            ], 200);
        } else {
            return response()->json([
                'message' => 'OK',
                'destinations' => $destinations,
                'hebergements' => $hebergements,
                'services' => $services,
                'servicespayants' => $servicespayants,
                'retours' => $retours,
                'pImage' => isset($p_Image) ? $p_Image : '',
                'sImage' => isset($s_Image) ? $s_Image : '',
                'tImage1' => isset($t1_Image) ? $t1_Image : '',
                'tImage2' => isset($t2_Image) ? $t2_Image : '',
                'tImage3' => isset($t3_Image) ? $t3_Image : '',
                'tImage4' => isset($t4_Image) ? $t4_Image : '',
            ], 200);
        }
    }

    public function delete_destination($id)
    {

        Service::where('destination_id', $id)->delete();
        Retours::where('destination_id', $id)->delete();
        $hebergements = Hebergement::where('destination_id', $id)->get();
        foreach ($hebergements as $hebergement) {
            Equipements::where('hebergement_id', $hebergement->id)->delete();

            $plannings = Planning::where('hebergement_id', $hebergement->id)->get();
            foreach ($plannings as $planning) {
                Period::where('planning_id', $planning->id)->delete();
            }
            Planning::where('hebergement_id', $hebergement->id)->delete();
        }
        Hebergement::where('destination_id', $id)->delete();

        $destination = DB::table('destinations')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'OK',
            'destination' => $destination
        ], 200);
    }

    public function create_destination(Request $req)
    {

        $pImage = Storage::disk('s3')->put('images', $req->pImage);
        $sImage = Storage::disk('s3')->put('images', $req->sImage);
        $tImage1 = Storage::disk('s3')->put('images', $req->tImage1);
        $tImage2 = Storage::disk('s3')->put('images', $req->tImage2);
        $tImage3 = Storage::disk('s3')->put('images', $req->tImage3);
        $tImage4 = Storage::disk('s3')->put('images', $req->tImage4);

        $requete = json_decode($req->destination);

        $destination = Destination::create([
            'name' => $requete->name,
            'city' => $requete->city,
            'description' => $requete->description,
            'address' => str_replace("\n", '<br />', $requete->address),
            'latitude' => $requete->latitude,
            'longitude' => $requete->longitude,
            'phone' => $requete->phone,
            'languages' => str_replace("\n", '<br />', $requete->languages),
            'mail' => $requete->mail,
            'reception' => str_replace("\n", '<br />', $requete->reception),
            'arrival' => $requete->arrival,
            'departure' => $requete->departure,
            'carte' => $requete->carte,
            'caution' => $requete->caution,
            'taxe' => $requete->taxe,
            'pImage' => $pImage = '1' ? '' : $pImage,
            'sImage' => $sImage = '1' ? '' : $sImage,
            'tImage1' => $tImage1 = '1' ? '' : $tImage1,
            'tImage2' => $tImage2 = '1' ? '' : $tImage2,
            'tImage3' => $tImage3 = '1' ? '' : $tImage3,
            'tImage4' => $tImage4 = '1' ? '' : $tImage4,
            'vehicule' => $requete->vehicule,
            'parking' => $requete->parking,
            'favorite' => $requete->favorite,
            'location' => $requete->location,
            'site' => $requete->site,
            'renseignement' => str_replace("\n", '<br />', $requete->renseignement)
        ]);


        foreach ($requete->services as $service) {
            Service::create([
                'text' => $service,
                'destination_id' => $destination->id
            ]);
        }

        foreach ($requete->servicespayants as $servicepayant) {
            Servicespayant::create([
                'text' => $servicepayant,
                'destination_id' => $destination->id
            ]);
        }

        foreach ($requete->retours as $retour) {
            Retours::create([
                'text' => $retour,
                'destination_id' => $destination->id
            ]);
        }



        return response()->json([
            'message' => 'OK',
            'destination' => $destination
        ], 200);
    }


    public function modify_destination(Request $req)
    {
        $requete = json_decode($req->destination);

        $destinationTemp = Destination::find($requete->id);

        $pImageTemp = $destinationTemp->pImage;
        $sImageTemp = $destinationTemp->sImage;
        $tImage1Temp = $destinationTemp->tImage1;
        $tImage2Temp = $destinationTemp->tImage2;
        $tImage3Temp = $destinationTemp->tImage3;
        $tImage4Temp = $destinationTemp->tImage4;

        if ($req->pImage != "undefined") {
            $pImage = Storage::disk('s3')->put('images', $req->pImage);
            $pImageTemp = $pImage;
        }

        if ($req->sImage != "undefined") {
            $sImage = Storage::disk('s3')->put('images', $req->sImage);
            $sImageTemp = $sImage;
        }

        if ($req->tImage1 != "undefined") {
            $tImage1 = Storage::disk('s3')->put('images', $req->tImage1);
            $tImage1Temp = $tImage1;
        }

        if ($req->tImage2 != "undefined") {
            $tImage2 = Storage::disk('s3')->put('images', $req->tImage2);
            $tImage2Temp = $tImage2;
        }

        if ($req->tImage3 != "undefined") {
            $tImage3 = Storage::disk('s3')->put('images', $req->tImage3);
            $tImage3Temp = $tImage3;
        }

        if ($req->tImage4 != "undefined") {
            $tImage4 = Storage::disk('s3')->put('images', $req->tImage4);
            $tImage4Temp = $tImage4;
        }

        $destination = Destination::where('id', $requete->id)->update(
            [
                'name' => $requete->name,
                'city' => $requete->city,
                'description' => $requete->description,
                'address' => str_replace("\n", '<br />', $requete->address),
                'latitude' => $requete->latitude,
                'longitude' => $requete->longitude,
                'phone' => $requete->phone,
                'languages' => str_replace("\n", '<br />', $requete->languages),
                'mail' => $requete->mail,
                'reception' => str_replace("\n", '<br />', $requete->reception),
                'arrival' => $requete->arrival,
                'departure' => $requete->departure,
                'carte' => $requete->carte,
                'caution' => $requete->caution,
                'taxe' => $requete->taxe,
                'pImage' => $pImageTemp,
                'sImage' => $sImageTemp,
                'tImage1' => $tImage1Temp,
                'tImage2' => $tImage2Temp,
                'tImage3' => $tImage3Temp,
                'tImage4' => $tImage4Temp,
                'vehicule' => $requete->vehicule,
                'parking' => $requete->parking,
                'favorite' => $requete->favorite,
                'location' => $requete->location,
                'site' => $requete->site,
                'renseignement' => str_replace("\n", '<br />', $requete->renseignement)
            ]
        );

        Service::where('destination_id', $requete->id)->delete();
        Servicespayant::where('destination_id', $requete->id)->delete();
        Retours::where('destination_id', $requete->id)->delete();

        foreach ($requete->services as $service) {
            Service::create([
                'text' => $service,
                'destination_id' => $requete->id
            ]);
        }

        foreach ($requete->servicespayants as $servicepayant) {
            Servicespayant::create([
                'text' => $servicepayant,
                'destination_id' => $requete->id
            ]);
        }

        foreach ($requete->retours as $retour) {
            Retours::create([
                'text' => $retour,
                'destination_id' => $requete->id
            ]);
        }

        return response()->json([
            'message' => 'OK',
            'destination' => $destination,
            'pImage' => $pImageTemp,
            'sImage' => $sImageTemp,
            'tImage1' => $tImage1Temp,
            'tImage2' => $tImage2Temp,
            'tImage3' => $tImage3Temp,
            'tImage4' => $tImage4Temp,
        ], 200);
    }


    public function importerDestinations(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Vérifiez si le fichier est valide, par exemple, vérifiez l'extension ou le type MIME
            if ($file->isValid()) {
                // Chemin temporaire pour le stockage du fichier
                $temporaryPath = $file->getRealPath();

                try {


                    // Chargement du fichier Numbers avec PhpSpreadsheet
                    $spreadsheet = IOFactory::load($temporaryPath);
                    $sheet = $spreadsheet->getActiveSheet();

                    $data = [];
                    $rowIterator = $sheet->getRowIterator();
                    $counter = 0;
                    foreach ($rowIterator as $row) {
                        if ($counter > 0) {
                            $name = $sheet->getCell('B' . $row->getRowIndex())->getValue();
                            $description = $sheet->getCell('G' . $row->getRowIndex())->getValue();
                            $localisation = $sheet->getCell('D' . $row->getRowIndex())->getValue();
                            $city = $sheet->getCell('E' . $row->getRowIndex())->getValue();
                            $address = nl2br($sheet->getCell('H' . $row->getRowIndex())->getValue());
                            $site = $sheet->getCell('I' . $row->getRowIndex())->getValue();
                            $phone = $sheet->getCell('J' . $row->getRowIndex())->getValue();
                            $mail = $sheet->getCell('K' . $row->getRowIndex())->getValue();
                            $latitude = $sheet->getCell('L' . $row->getRowIndex())->getValue();
                            $longitude = $sheet->getCell('M' . $row->getRowIndex())->getValue();

                            $reception= nl2br($sheet->getCell('Q' . $row->getRowIndex())->getValue());
                            $map = $sheet->getCell('R' . $row->getRowIndex())->getValue();
                            $language = nl2br($sheet->getCell('S' . $row->getRowIndex())->getValue());
                            $parking = $sheet->getCell('T' . $row->getRowIndex())->getValue();
                            $vehicule = $sheet->getCell('U' . $row->getRowIndex())->getValue();
                            $arrive = $sheet->getCell('V' . $row->getRowIndex())->getValue();
                            $depart = $sheet->getCell('W' . $row->getRowIndex())->getValue();
                            $favorite = $sheet->getCell('Y' . $row->getRowIndex())->getValue();

                            $retours = $sheet->getCell('X' . $row->getRowIndex())->getValue();

                            $renseignement = str_replace("\n", "<br />", $sheet->getCell('AB' . $row->getRowIndex())->getValue());

                            $data = [
                                'name' => $name,
                                'city' => $city,
                                'description' => $description,
                                'address' => $address,
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                                'phone' => $phone,
                                'mail' => $mail,
                                'location' => $localisation,
                                'site' => $site,
                                'reception' => $reception,
                                'arrival' => $arrive.'h',
                                'departure' => $depart.'h',
                                'carte' => $map,
                                'pImage' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'sImage' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'tImage1' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'tImage2' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'tImage3' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'tImage4' => 'images/sDZjT0LJZxqphDutXyGDJuelL1R8saLLOhLuhvUF.jpg',
                                'languages' => $language,
                                'vehicule' => $vehicule,
                                'parking' => $parking,
                                'favorite' => $favorite,
                                'renseignement' => $renseignement
                            ];

                            Destination::create($data);

                            $id = $sheet->getCell('P' . $row->getRowIndex())->getValue();

                            $adores = explode("\n",$sheet->getCell('Z' . $row->getRowIndex())->getValue());
                            $services = explode("\n",$sheet->getCell('AA' . $row->getRowIndex())->getValue());

                            if (count($adores) != 1) {
                                foreach ($adores as $adore) {
                                    if ($adore != '') {
                                        $dataAdores = [
                                            'text' => $adore,
                                            'destination_id' => $id
                                        ];

                                        Retours::create($dataAdores);
                                    }
                                }
                            }


                            if (count($services) != 1) {
                                foreach ($services as $service) {
                                    if ($service != '') {
                                        $dataServices = [
                                            'text' => $service,
                                            'destination_id' => $id
                                        ];

                                        Service::create($dataServices);
                                    }
                                }
                            }
                        }
                        $counter++;
                    }

                    // Retournez une réponse appropriée
                    return response()->json(['message' => 'Importation des destinations réussie']);
                } catch (\Exception $e) {
                    // Une erreur s'est produite lors du traitement du fichier
                    return response()->json(['message' => 'Une erreur s\'est produite lors du traitement du fichier', 'error' => $e->getMessage()], 500);
                }
            } else {
                // Le fichier est invalide ou a une extension incorrecte
                return response()->json(['message' => 'Le fichier est invalide ou a une extension incorrecte'], 400);
            }
        } else {
            // Aucun fichier n'a été envoyé
            return response()->json(['message' => 'Aucun fichier n\'a été envoyé'], 400);
        }
    }
}
