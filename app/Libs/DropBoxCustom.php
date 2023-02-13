<?php


namespace App\Libs;


use Dcblogdev\Dropbox\Facades\Dropbox;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class DropBoxCustom
 *
 * @see custom from \Dcblogdev\Dropbox\Dropbox
 *
 * @package App\Libs
 */
class DropBoxCustom
{
    static private function forceStartingSlash($string)
    {
        if (substr($string, 0, 1) !== "/") {
            $string = "/$string";
        }

        return $string;
    }

    /**
     * get thumbnail
     *
     * @param string $path
     * @param string $format
     * @param string $size
     * @param string $destFolder
     *
     * @return BinaryFileResponse
     * @throws GuzzleException
     * @throws Exception
     */
    public static function getThumbnail(string $path, string $format = "jpeg", string $size = "w128h128", string $destFolder = ''): BinaryFileResponse
    {
        $path = self::forceStartingSlash($path);

        try {
            $client = new Client;

            $response = $client->post("https://content.dropboxapi.com/2/files/get_thumbnail_v2", [
                'headers' => [
                    'Authorization' => 'Bearer ' . Dropbox::getAccessToken(),
                    'Dropbox-API-Arg' => json_encode([
                        "format"=> $format,
                        "mode" => "strict",
                        "size" => $size,
                        "resource" => [
                            ".tag" => "path",
                            "path" => $path,
                        ]
                    ])
                ]
            ]);
            $header = json_decode($response->getHeader('Dropbox-Api-Result')[0], true);
            $body = $response->getBody()->getContents();
            $fileMetadata = $header['file_metadata'];

            if (empty($destFolder)){
                $destFolder = 'dropbox-temp';

                if (! is_dir($destFolder)) {
                    mkdir($destFolder);
                }
            }

            file_put_contents($destFolder.$fileMetadata['name'], $body);

            return response()->download($destFolder.$fileMetadata['name'], $fileMetadata['name'])->deleteFileAfterSend();

        } catch (ClientException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * get thumbnail bat
     *
     * @param array  $pathList
     * @param string $format
     * @param string $size
     * @param string $destFolder
     *
     * @return array [['file' => $file, 'fileMetadata' => $fileMetadata]]
     * @throws GuzzleException
     * @throws Exception
     */
    public static function getThumbnailBat(array $pathList, string $format = "jpeg", string $size = "w128h128", string $destFolder = ''): array
    {
        $entries = [];
        foreach ($pathList as $path){
            $entries[] = [
                "format"=> $format,
                "mode" => "strict",
                "path" => self::forceStartingSlash($path),
                "size" => $size
            ];
        }

        try {
            $client = new Client;

            $response = $client->post("https://content.dropboxapi.com/2/files/get_thumbnail_batch", [
                'headers' => [
                    'Authorization' => 'Bearer ' . Dropbox::getAccessToken(),
                    'Content-Type: application/json',
                ],
                'json' =>
                    [
                        "entries" => $entries
                    ]
                ]
            );

            if (empty($destFolder)){
                $destFolder = 'dropbox-temp';

                if (! is_dir($destFolder)) {
                    mkdir($destFolder);
                }
            }

            $contents = json_decode($response->getBody()->getContents());

            $files = [];
            foreach ($contents->entries as $key => $entry){
                if(property_exists($entry, 'failure')){
                    $path = $pathList[$key];
                    throw new Exception("File is undefined. path :{$path}");
                }

                $thumbnail = $entry->thumbnail;
                $fileMetadata = $entry->metadata;
                file_put_contents($destFolder.$fileMetadata->name, base64_decode($thumbnail));
                $files[] = [
                    'file' => response()->download($destFolder.$fileMetadata->name, $fileMetadata->name)->deleteFileAfterSend(),
                    'fileMetadata' => $fileMetadata
                ];
            }

            return $files;

        } catch (ClientException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
