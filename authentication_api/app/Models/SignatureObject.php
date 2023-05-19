<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureObject extends Model
{
    protected $attributes = [
        'signature_type'=>'',
        'signature_targetHash'=>'',
    ];

    public function __construct($json) {
        $data = json_decode($json);
        $this->sign_type = $data->signature->type;
        $this->sign_targetHash = $data->signature->targetHash;
    }
}
