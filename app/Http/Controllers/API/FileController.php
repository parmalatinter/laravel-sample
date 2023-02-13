<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Libs\DropBoxCustom;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends AppBaseController
{

    /**
     * index of files of thumbnail.
     *
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        // return DropBoxCustom::getThumbnail("/test/white_cub.PNG");
        $files = DropBoxCustom::getThumbnailBat(["/test/white_cub.PNG", "/test/DSCN0289.JPG"], "jpeg", "w256h256");
        $totalRowCount = 1;

        return $this->sendResponse([
            'rows' => $files,
            'totalRowCount' => $totalRowCount
        ], 'Files retrieved successfully');
    }
}
