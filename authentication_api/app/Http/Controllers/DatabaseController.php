<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
 
class DatabaseController extends Controller
{
    /**
     * Show a list of all of the application's users.
     */
    
    public function store($id, $fileType, $verificationResult, $timestamp)
    {
        DB::table('verifyDB')->insert([
            'id' => $id,
            'file_type' => $fileType,
            'verification_result' => $verificationResult,
            'timestamp' => $timestamp
        ]);
    }
}