<?php

namespace PRStats\Http\Controllers;

use Illuminate\Http\Request;
use PRStats\Models\User;

class ProfileController extends Controller
{

    public function index($pid)
    {
        $user = \Auth::check();

        return view('prstats.profile.index', [
            'user' => $user,
        ]);
    }

    public function authenticate($pid, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403);
        }

        $user = User::where('id', $pid)->firstOrFail();

        \Auth::login($user, true);

        $next = $request->query('next');

        return $next ? redirect(url($next)) : redirect(url('/profile'));
    }

    public function logout()
    {
        \Auth::logout();
        return redirect(url('/'));
    }

}
