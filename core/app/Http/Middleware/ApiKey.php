<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey as ApiKeyModel;
use Illuminate\Http\Request;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request;
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     *
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->isMethod('GET')) {
            $apiKey = $request->apikey;
        } else {
            $header = $request->header();
            if (!array_key_exists('apikey', $header)) {
                return apiResponse(false, 422, null, ['API key must be required on request header']);
            }
            $apiKey = @$header['apikey'][0];
        }

        if (!$apiKey) {
            return apiResponse(false, 422, null, ['API key must be required']);
        }

        $exitsApiKey = ApiKeyModel::where('status', 1)->where('key', $apiKey)->exists();

        if (!$exitsApiKey) {
            return apiResponse(false, 406, null, ['API Key mismatch', 'API key not acceptable']);
        }

        return $next($request);
    }
}
