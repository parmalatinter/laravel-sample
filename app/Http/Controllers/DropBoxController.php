<?php


namespace App\Http\Controllers;


use App\Libs\DropBoxCustom;
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
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index()
    {
        return DropBoxCustom::getThumbnail("/test/white_cub.PNG");
        //return DropBoxCustom::getThumbnailBat("/test/white_cub.PNG");
    }
}