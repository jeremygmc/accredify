<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictFileSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maxFileSize = 2048; // Maximum file size in kilobytes (2MB)

        if ($request->hasFile('file') && $request->file('file')->getSize() > $maxFileSize * 1024) {
            return response()->json(['error' => 'File size exceeds the maximum limit.'], 200);
        }
        
        return $next($request);
    }
}
