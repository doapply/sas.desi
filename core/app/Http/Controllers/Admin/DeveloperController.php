<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;

class DeveloperController extends Controller
{
    public function apiDocs()
    {

        $pageTitle  = "API Documentation";
        $admin      = auth()->guard('admin')->user();
        $errorCodes = [
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            406 => 'Not Acceptable',
            422 => 'Unprocessable Entity',
            424 => 'Failed Dependency',
            500 => 'Internal Server Error',
        ];
        return view('admin.developer.docs', compact('pageTitle', 'admin', 'errorCodes'));
    }

    public function regenerateApiKey()
    {

        $admin = auth()->guard('admin')->user();
        ApiKey::where('admin_id', $admin->id)->where('status', 1)->update(['status' => 0]);

        $apiKey           = new ApiKey();
        $apiKey->key      = getTrx(40);
        $apiKey->admin_id = $admin->id;
        $apiKey->save();

        $notify[] = ['success', "API key regenerate successfully"];
        return back()->withNotify($notify);
    }
}
