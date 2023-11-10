<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class RemoveBackgroundController extends Controller
{
    public function index()
    {
        $imageUrl = "https://fptshop.com.vn/Uploads/Originals/2022/12/21/638072287029793818_Xiaomi-Redmi-10A-2GB-32GB-xam-1.jpg";

        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://api.remove.bg/v1.0/removebg', [
            'multipart' => [
                [
                    'name'     => 'image_url',
                    'contents' => $imageUrl
                ],
                [
                    'name'     => 'size',
                    'contents' => 'auto'
                ]
            ],
            'headers' => [
                'X-Api-Key' => '6pDeFv5TX8LqfBm9Ep5gZFx6'
            ]
        ]);

        $responseData = $res; // Chuyển đổi nội dung JSON thành mảng PHP
        dd($responseData);
    }
}
