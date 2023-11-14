<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Events\ResetPasswordEmail;

class PasswordResetController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            $resetLink = $this->generateResetLink($request->email);

            // Déclenche l'événement avec le lien de réinitialisation
            event(new ResetPasswordEmail($resetLink));
            return response()->json(['message' => 'E-mail de réinitialisation envoyé']);
        } else {
            return response()->json(['message' => 'Échec de la réinitialisation du mot de passe'], 400);
        }
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        // Assurez-vous de vérifier que le jeton correspond à l'utilisateur
        if (! hash_equals($user->reset_password_token, $request->token)) {
            return response()->json(['message' => 'Jeton de réinitialisation invalide.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->reset_password_token = null; // Réinitialisez le jeton après utilisation

        $user->save();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès']);
    }
}
