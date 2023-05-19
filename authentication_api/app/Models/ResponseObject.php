<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseObject 
{
    protected $attributes = [
        'issuer' => 'Not found',
        'result' => '',
    ];

    public function setIssuer($issuer) {
        $this->issuer = $issuer;
    }
    public function setResult($result) {
        $this->result = $result;
    }
    public function getData() {
        $data = array("issuer"=>$this->issuer, "result"=>$this->result);
        $final = array("data"=>$data);
        return response()->json($data, $status = 200, $headers = [], $options = 0);
    }

}
