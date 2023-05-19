<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Support\Facades\Http;
  
class APICall extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function accessDNSAPI($url)
    {
        $apiURL = 'https://dns.google/resolve?name=' . $url .'&type=TXT';

        $response = Http::get($apiURL);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
  
        return $responseBody["Answer"];
    }
}