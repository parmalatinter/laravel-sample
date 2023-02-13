<?php


namespace App\Libs;


use Dcblogdev\Dropbox\Facades\Dropbox;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

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
     * @throws GuzzleException
     * @throws Exception
     */
    public static function getThumbnail($path, $destFolder = '')
    {
        $path = self::forceStartingSlash($path);

        try {
            $client = new Client;

            $response = $client->post("https://content.dropboxapi.com/2/files/get_thumbnail_v2", [
                'headers' => [
                    'Authorization' => 'Bearer ' . Dropbox::getAccessToken(),
                    'Dropbox-API-Arg' => json_encode([
                        "format"=> "jpeg",
                        "mode" => "strict",
                        "size" => "w64h64",
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
     * @throws GuzzleException
     * @throws Exception
     */
    public static function getThumbnailBat($path)
    {
        $path = self::forceStartingSlash($path);

        try {
            $client = new Client;

            $response = $client->post("https://content.dropboxapi.com/2/files/get_thumbnail_batch", [
                'headers' => [
                    'Authorization' => 'Bearer ' . Dropbox::getAccessToken(),
                    'Content-Type: application/json',
                ],
                'json' =>
                    [
                        "entries" =>[
                            [
                                "format"=> "jpeg",
                                "mode" => "strict",
                                "path" => $path,
                                "size" => "w64h64"
                            ]]
                    ]

            ]);

            return $response->getBody()->getContents();

        } catch (ClientException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
