<?php


namespace App\Libs;


use Dcblogdev\Dropbox\Facades\Dropbox;
use GuzzleHttp\Client;

class DropBoxCustom
{
    static private function forceStartingSlash($string)
    {
        if (substr($string, 0, 1) !== "/") {
            $string = "/$string";
        }

        return $string;
    }

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
                            "path" => "/test/white_cub.PNG",
                        ]
                    ])
                ]
            ]);
            $header = ['name' => 'test.jpeg'];
            $body = $response->getBody()->getContents();

            if (empty($destFolder)){
                $destFolder = 'dropbox-temp';

                if (! is_dir($destFolder)) {
                    mkdir($destFolder);
                }
            }

            file_put_contents($destFolder.$header['name'], $body);

            return response()->download($destFolder.$header['name'], $header['name'])->deleteFileAfterSend();

        } catch (ClientException $e) {
            throw new Exception($e->getResponse()->getBody()->getContents());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

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
