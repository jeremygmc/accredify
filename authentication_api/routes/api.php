<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APICall;
use App\Http\Controllers\DatabaseController;
use App\Models\ResponseObject;
use App\Models\RequestObject;
use App\Models\SignatureObject;


function checkEmptyOrNotSet($check, $message) {
    if (empty($check) || isset($check) == FALSE ) {
        return $message;
    }
}

function checkRecipientNameEmail($data) {
    if (empty($data['data']['recipient']['name']) || isset($data['data']['recipient']['name']) == FALSE ) {
        return 'invalid_recipient';
    }
    if (empty($data['data']['recipient']['email']) || isset($data['data']['recipient']['email']) == FALSE ) {
        return 'invalid_recipient';
    }
    
    return 'verified';
}

function checkIssuerIdentityProof($data) {
    if (empty($data['data']['issuer']['name']) || isset($data['data']['issuer']['name']) == FALSE ) {
        echo $data['data']['issuer']['name'];
        return 'invalid_issuer';
    }
    if (empty($data['data']['issuer']['identityProof']['type']) || isset($data['data']['issuer']['identityProof']['type']) == FALSE ) {
        echo $data['data']['issuer']['identityProof']['type'];
        return 'invalid_issuer';
    }
    if (empty($data['data']['issuer']['identityProof']['key']) || isset($data['data']['issuer']['identityProof']['key']) == FALSE ) {
        echo $data['data']['issuer']['identityProof']['key'];
        return 'invalid_issuer';
    }
    if (empty($data['data']['issuer']['identityProof']['location']) || isset($data['data']['issuer']['identityProof']['location']) == FALSE ) {
        echo $data['data']['issuer']['identityProof']['location'];
        return 'invalid_issuer';
    }

    return 'verified';
}

function checkDNSTXT(string $key, string $location) {
    $results = (new APICall)->accessDNSAPI($location);
    foreach($results as $result) {
        if (stripos($result["data"], $key) !== false) {
            return 'condition 2 verified';
        }
    };
    return 'invalid_issuer';
}

function hashOperation(RequestObject $request, SignatureObject $signature) {
    try {
        $hashed_array = array();
        foreach($request->toArray() as $key => $value) {
            $object = (object) [
                $key=>$value
            ];
            $objstring = json_encode($object);
            
            $hash = hash('sha256', $objstring);
            $hashed_array[] = $hash;
        };

        $sorted_array = sort($hashed_array);
        $final_hash = hash('sha256', $sorted_array);

        if (!$final_hash==$signature->signature_target_hash) {
            return 'invalid_signature';
        }
        return 'verified';
    }
    catch (Exception $e) {
        return 'invalid_signature';
    }
    
}

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('user', function (Request $request) {
    return $request->user();
});

Route::get('test', function () {
    $json = \File::get("no-recipient-name.json");
    $data = json_decode($json, true);
    $request = new RequestObject;
    $request->fill($data['data']);
    print_r($request->recipient_name);
});

Route::middleware(['file.size'])->get('run_all_functions', function () {
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
        (new DatabaseController)->store($request->id, 'JSON', $result, date('Y-m-d H:i:s'));
        $response->setIssuer($request->issuer_name);
        $response->setResult($result);
        return $response->getData();
    }

    $result = hashOperation($request, $signature);
    if (stripos($result,'invalid')==0) {
        (new DatabaseController)->store($request->id, 'JSON', $result, date('Y-m-d H:i:s'));
        $response->setIssuer($request->issuer_name);
        $response->setResult($result);
        return $response->getData();
    }

    (new DatabaseController)->store($request->id, 'JSON', $result, date('Y-m-d H:i:s'));
    $response->setIssuer($request->issuer_name);
    $response->setResult($result);
    return $response->getData();
});