<?php

namespace PRStats\Http\Controllers;

use Illuminate\Http\Request;
use PRStats\Models\User;
use PRStats\Notifications\UserLoginRequestNotification;

class UserController extends Controller
{

    public function loginRequest(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $email = $request->email;

        /** @var User $user */
        $user = User::firstOrNew([
            'email' => $email
        ], [
            'name' => explode('@', $email)[0],
//            'password' => bcrypt(Str::random()),
        ]);

        $newUser = !$user->exists;

        if (!$user->exists) {
            $user->save();
        }

        $user->notify(new UserLoginRequestNotification($newUser));

        return response()->json([
            'message' => 'Check your email to get your login link.',
        ]);
    }

    public function login($id, Request $request)
    {
        if (!$request->hasValidSignature(false)) {
            abort(401);
        }

        $user = User::findOrFail($id);

        \Auth::login($user, true);

        return redirect()->route('claim.index');
    }

}
