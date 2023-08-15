<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInfoEmail;
use App\Mail\AdminContactEmail;
use App\Mail\AdminContactCEEmail;

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

        if($req->password != "") {
            $user = User::where('id', $req->id)->update(
                [
                    'name' => $req->name,
                    'city' => $req->city,
                    'email' => $req->email,
                    'address' => $req->address,
                    'phone' => $req->phone,
                    'role' => $req->role,
                    'password' => Hash::make($req->password),
                ]
            );
        } else {
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
        }
        

        return response()->json([
            'message' => 'OK',
            'user' => $user
        ], 200);
    }

    public function send_client_info(Request $req) {
        $clientName = $req->name;
        $clientEmail = $req->email;
        $clientPassword = $req->password;
        // Autres informations sur le client

        // Envoyer l'e-mail
        Mail::to($clientEmail)->send(new UserInfoEmail($clientName, $clientEmail, $clientPassword));

        if($clientPassword != "") {
            $user = User::where('id', $req->id)->update(
                [
                    'name' => $req->name,
                    'city' => $req->city,
                    'email' => $req->email,
                    'address' => $req->address,
                    'phone' => $req->phone,
                    'role' => $req->role,
                    'password' => Hash::make($req->password),
                ]
            );
        } else {
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
        }

        return response()->json([
            'message' => 'OK',
            'user' => $user
        ], 200);
    }

    public function send_admin_form(Request $req) {
        $clientName = $req->name;
        $clientEmail = $req->mail;
        $clientMessage = $req->message;
        $clientPhone = $req->phone;
        $clientHebergement = $req->hebergement;
        $clientFirstName = $req->firstName;

        // Autres informations sur le client
        $adminMail = 'jrgabet@hotmail.fr';

        // Envoyer l'e-mail
        Mail::to($adminMail)->send(new AdminContactEmail($clientName, $clientEmail, $clientMessage, $clientPhone, $clientHebergement, $clientFirstName));


        return response()->json([
            'message' => 'Mail bien envoyé',
        ], 200);
    }

    public function send_admin_ce_form(Request $req) {
        $clientName = $req->name;
        $clientEmail = $req->mail;
        $clientMessage = $req->message;
        $clientPhone = $req->phone;
        $clientSociety = $req->society;
        $clientFirstName = $req->firstName;

        // Autres informations sur le client
        $adminMail = 'jrgabet@hotmail.fr';

        // Envoyer l'e-mail
        try {
            Mail::to($adminMail)->send(new AdminContactCEEmail($clientName, $clientEmail, $clientMessage, $clientPhone, $clientSociety, $clientFirstName));
        } catch (\Exception $e) {
            // Capturer l'exception en cas d'erreur lors de l'envoi de l'e-mail.
            // Vous pouvez gérer l'erreur ici en fonction de vos besoins.
            // Par exemple, vous pourriez enregistrer le message d'erreur dans un fichier de journal.
            // Vous pourriez également afficher un message d'erreur à l'utilisateur, etc.
            $errorMessage = "Une erreur est survenue lors de l'envoi de l'e-mail : " . $e->getMessage();
            // Faites quelque chose avec l'erreur, comme l'enregistrer dans un journal.
            Log::error($errorMessage);
        }

        return response()->json([
            'message' => 'Mail bien envoyé',
        ], 200);
    }

    public function create_client(Request $request)
    {
        // $user = User::create([
        //     'name' => $req->name,
        //     'city' => $req->city,
        //     'email' => $req->email,
        //     'address' => $req->address,
        //     'phone' => $req->phone,
        //     'role' => $req->role,
        //     'password' => $req->password
        // ]);

        // return response()->json([
        //     'message' => 'OK',
        //     'user' => $user
        // ], 200);

        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'ce',
        ]);

        $clientName = $request->name;
        $clientEmail = $request->email;
        $clientPassword = $request->password;
        // Autres informations sur le client

        // Envoyer l'e-mail
        Mail::to($clientEmail)->send(new UserInfoEmail($clientName, $clientEmail, $clientPassword));

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json(['id' => $user->id, 'role' => $user->role]);
    }
}
