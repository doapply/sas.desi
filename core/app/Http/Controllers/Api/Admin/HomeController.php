<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function unAuthenticated()
    {
        return apiResponse(false, 401, "Unauthorized", ['Invalid or missing access token']);
    }
}
