<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Libs\DropBoxCustom;
use Dcblogdev\Dropbox\Facades\Dropbox;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class FileController
 *
 * @see https://github.com/dcblogdev/laravel-dropbox
 * @package App\Http\Controllers\API
 */
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
        $filesInfo = Dropbox::post('files/list_folder', ['path' =>'/test']);
        $pathList = [];
        foreach ($filesInfo['entries'] as $entry){
            $pathList[] = $entry['path_lower'];
        }
        $files = DropBoxCustom::getThumbnailBat($pathList, "jpeg", "w256h256");
        $totalRowCount = 1;

        return $this->sendResponse([
            'rows' => $files,
            'totalRowCount' => $totalRowCount
        ], 'Files retrieved successfully');
    }

    public function link(Request $request)
    {
        $input = $request->all();
        return Dropbox::post('files/get_temporary_link', ['path' => $input['path_display']]);
    }

    public function store(Request $request): JsonResponse
    {

        $images = $request->file('images') ?? [];
        foreach ($images as $image){
            $imageName = $image->getClientOriginalName();
            Log::info($imageName);
            $path = $image->storeAS('/test',$imageName);
            $path = storage_path("app/{$path}");
            Dropbox::files()->upload('/test', $path);
        }


        return $this->sendResponse([
            'images' => $images
        ], 'Files saved successfully');
    }

    /*
     *
     */
    public function destroy(Request $request): JsonResponse
    {
        $path = $request->get('path');
        $deleteInfo = Dropbox::files()->delete($path);
        $totalRowCount = 1;

        return $this->sendResponse([
            'result' => !empty($deleteInfo),
            'totalRowCount' => $totalRowCount
        ], 'Files retrieved successfully');
    }
}
