<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APICall;
use App\Http\Controllers\DatabaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
class ResponseObject
{
    public $issuer;
    public $result;

    public function __construct() {
        $this->issuer = 'Not found';
    }

    public function setIssuer($issuer) {
        $this->issuer = $issuer;
    }
    public function setResult($result) {
        $this->result = $result;
    }
    public function getData() {
        $data = array("issuer"=>$this->issuer, "result"=>$this->result);
        $final = array("data"=>$data);
        http_response_code(200);
        return json_encode($final);
    }
}

class RequestObject
{
    public $id;
    public $documentName;
    public $recipientName;
    public $recipient_email;
    public $issuer_name;
    public $issuer_proof_type;
    public $issuer_proof_key;
    public $issuer_proof_location;
    public $issued;

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


    // add key value pairs to return input as json, to check that code can read json file properly
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->documentName,
            'recipient.name' => $this->recipientName,
            'recipient.email' => $this->recipient_email,
            'issuer.name' => $this->issuer_name,
            'issuer.identityProof.type' => $this->issuer_proof_type,
            'issuer.identityProof.key' => $this->issuer_proof_key,
            'issuer.identityProof.location' => $this->issuer_proof_location,
            'issued' => $this->issued
        ];
    }

    public function toJson() {
        return json_encode($this->toArray());
    }

}

class SignatureObject 
{
    public $sign_type;
    public $sign_targetHash;

    public function __construct($json) {
        $data = json_decode($json);
        $this->sign_type = $data->signature->type;
        $this->sign_targetHash = $data->signature->targetHash;
    }
}

Route::get('/', function () {
    $json = \File::get("no-recipient-name.json");
    
    $response = new ResponseObject();

    $data = json_decode($json,true);
    $result = checkRecipientNameEmail($data);
    
    if (stripos($result,'invalid')==0) {
        (new DatabaseController)->store($data['data']['id'], 'JSON', $result, date('Y-m-d H:i:s'));
        $response->setIssuer($data['data']['issuer']['name']);
        $response->setResult($result);
        return $response->getData();
    }

    $result = checkIssuerIdentityProof($data);

    if (stripos($result,'invalid')==0) {
        (new DatabaseController)->store($data['data']['id'], 'JSON', $result, date('Y-m-d H:i:s'));
        $response->setIssuer($data['data']['issuer']['name']);
        $response->setResult($result);
        return $response->getData();
    }

    $request = new RequestObject($json);
    $signature = new SignatureObject($json);
    $result = checkDNSTXT($request->issuer_proof_key, $request->issuer_proof_location);
    if (stripos($result,'invalid')==0) {
        (new DatabaseController)->store($data->data->id, 'JSON', $result, date('Y-m-d H:i:s'));
        $response->setIssuer($data->data->issuer->name);
        $response->setResult($result);
        return $response->getData();
    }

    echo hashOperation($request);
    
    return ['Laravel' => app()->version()];
})->middleware('file.size');

require __DIR__.'/auth.php';
