<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestObject extends Model
{
    protected $attributes = [
        'id'=>'',
        'documentName'=>'',
        'recipientName'=>'',
        'recipient_email'=>'',
        'issuer_name'=>'',
        'issuer_identityProof_type'=>'',
        'issuer_identityProof_key'=>'',
        'issuer_identityProof_location'=>'',
        'issued'=>'',
    ];
    protected $fillable = [
        'options->enabled',
    ];

    public function __construct($json) {
        $data = json_decode($json);
        $this->id = $data->data->id;
        $this->documentName = $data->data->name;

        $this->recipientName = $data->data->recipient->name;
        $this->recipient_email = $data->data->recipient->email;

        $this->issuer_name = $data->data->issuer->name;
        $this->issuer_proof_type = $data->data->issuer->identityProof->type;
        $this->issuer_proof_key = $data->data->issuer->identityProof->key;
        $this->issuer_proof_location = $data->data->issuer->identityProof->location;

        $this->issued = $data->data->issued;
        }
}
