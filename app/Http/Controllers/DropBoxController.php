<?php


namespace App\Http\Controllers;


use Dcblogdev\Dropbox\Facades\Dropbox;
use Illuminate\Contracts\Support\Renderable;

class DropBoxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {

        return Dropbox::files()->listContents();
    }
}
