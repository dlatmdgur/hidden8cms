<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class BlankController extends Controller
{

    private $adminUser;

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
     * Show the nothing.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('blank.blank');
    }

}
