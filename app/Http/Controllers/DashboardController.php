<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return RedirectResponse|Redirector
     */
    public function index()
    {
        // check permission
        $user = Auth::user();
        if (!$user->hasPermissionTo('member') && $user->hasPermissionTo('exchange')) {
            return redirect('/statistics/exchange');
        } else {
            if (!$user->hasPermissionTo('member')) {
                return redirect('/blank');
            } else {
                return redirect('/member/info');
            }
        }

        //return view('dashboard');
    }
}
