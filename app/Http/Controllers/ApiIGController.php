<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class ApiIGController extends BaseController
{
    public function getInstagramFeed()
    {
        $token = env('IG_TOKEN');

        $response = Http::get("https://graph.instagram.com/me/media", [
            'fields' => 'id,caption,media_url,permalink,timestamp',
            'access_token' => $token
        ]);

        $data = $response->json();

        return $data['data'][0]; // post terakhir
    }
}
