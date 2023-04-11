<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class DeviceController extends Controller
{

    public function index()
    {
        $pageTitle    = 'Manage Device';
        $query        = Device::filter(['status'])->latest();
        $allDevice    = $query->paginate(getPaginate());

        //for scan qr code,host URL needed for api base url on app
        if (cache()->has('qr_code_image_src')) {
            $qrCodeImgSrc = cache()->get('qr_code_image_src');
        } else {
            $adminId      = encrypt(auth()->guard('admin')->user()->id);
            $rootUrl      = route('home');
            $qrCodeImgSrc = cryptoQR($adminId . "HOST" . $rootUrl);
            cache()->put('qr_code_image_src', $qrCodeImgSrc);
        }

        return view('admin.device.index', compact('pageTitle', 'allDevice', 'qrCodeImgSrc'));
    }
}
