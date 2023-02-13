<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Libs\DropBoxCustom;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class DropBoxController extends AppBaseController
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
     * index of files of thumbnail.
     *
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function index()
    {
        // return DropBoxCustom::getThumbnail("/test/white_cub.PNG");
        $files = DropBoxCustom::getThumbnailBat(["/test/white_cub.PNG", "/test/DSCN0289.JPG"]);

        return $this->sendResponse($files, 'success');
    }
}
