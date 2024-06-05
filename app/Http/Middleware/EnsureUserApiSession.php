<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class EnsureUserApiSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization') ?? '';
        if($token == '') {
            return response()->json(["error"=> "Unauthorized"],401);
        }
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get('http://127.0.0.1:8000/api/me'); // TODO: We need to set the URL in an environment variable. But our middleware works great!.

        if($response->successful()) {
            $request->merge(['user' => (array) $response->object()]);
            return $next($request);
        }
        if($response->status() == 401) {
            return response()->json(["error"=> "Unauthorized"],401);
        }
        return response()->json(["error" => "There was an error checking current user"], $response->status());
    }
}
