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
 * Class FileApiController
 *
 * @see https://github.com/dcblogdev/laravel-dropbox
 * @package App\Http\Controllers\API
 */
class FileApiController extends AppBaseController
{

    /**
     * index of files of thumbnail.
     *
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function index(Request $request)
    {
        $path = $request->get('path')?? '';
        $search = $request->get('search')?? '';
        if($path) $path = "/{$path}";
        $cursor = $request->get('cursor')?? null;
        $pathList = [];
        $key = 'entries';
        if($search){
            $filesInfo = Dropbox::post('files/search_v2', ['query' => $search]);
            foreach ($filesInfo['matches'] as $match){
                $pathList[] = $match['metadata']['metadata']['path_lower'];
            }
            $key = 'matches';
        }elseif($cursor){
            $filesInfo = Dropbox::post('files/list_folder/continue', ['cursor' => $cursor]);
            foreach ($filesInfo['entries'] as $entry){
                $pathList[] = $entry['path_lower'];
            }
        }else{
            $filesInfo = Dropbox::post('files/list_folder', ['path' => $path, 'limit' => 20]);
            foreach ($filesInfo['entries'] as $entry){
                $pathList[] = $entry['path_lower'];
            }
        }


        $files = DropBoxCustom::getThumbnailBat($pathList, "jpeg", "w256h256");
        $totalRowCount = 1;
        $rows = array_map(function ($index, $entry) use ($files){
                return array_merge($entry,$files[$index]?? [] );
            }, array_keys($filesInfo[$key]), array_values($filesInfo[$key])
        );

        return $this->sendResponse([
            'rows' => $rows,
            'totalRowCount' => $filesInfo['has_more'] ? 20 : 10,
            'hasMore' => $filesInfo['has_more'] ?? false,
            'cursor' => $filesInfo['cursor'] ?? '',
        ], 'Files retrieved successfully');
    }

    public function link(Request $request)
    {
        $input = $request->all();
        return Dropbox::post('files/get_temporary_link', ['path' => $input['path_display']]);
    }

    public function store(Request $request): JsonResponse
    {

        $files = $request->file('files') ?? [];
        foreach ($files as $file){
            $fileName = $file->getClientOriginalName();
            Log::info($fileName);
            $path = $file->storeAS('/test',$fileName);
            $path = storage_path("app/{$path}");
            Dropbox::files()->upload('/test', $path);
        }


        return $this->sendResponse([
            'files' => $files
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
