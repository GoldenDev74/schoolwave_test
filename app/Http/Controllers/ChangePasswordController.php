<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier si le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->password_changed = true;
        $user->save();

        // Reconnecter l'utilisateur avec le nouveau mot de passe
        Auth::login($user);

        // Rediriger vers la page d'accueil
        return redirect()->route('home')->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}
