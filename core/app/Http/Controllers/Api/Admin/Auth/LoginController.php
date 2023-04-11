<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use Exception;
use App\Models\Admin;
use App\Models\Device;
use App\Events\DeviceAdd;
use App\Events\DeviceLogOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function loginWithQrCode(Request $request)
    {
        $validator    = Validator::make($request->all(), [
            'scan_data'       => 'required|string',
            'device_id'       => 'required',
            'device_name'     => 'required',
            'device_model'    => 'required',
            'android_version' => 'required',
            'app_version'     => 'required',
            'sim'             => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable Entity", $validator->errors()->all());
        }
        $scanData = explode("HOST", $request->scan_data);
        try {
            $adminId = decrypt(@$scanData[0]);
            $admin   = Admin::find($adminId);
            if (!$admin) {
                return   apiResponse(false, 406, null, ['Admin not found']);
            }
            if (auth()->guard('admin')->loginUsingId($adminId)) {

                $device = Device::where('device_id', $request->device_id)->first();

                if (!$device) {

                    $device                  = new Device();
                    $device->device_id       = $request->device_id;
                    $device->device_name     = $request->device_name;
                    $device->device_model    = $request->device_model;
                    $device->android_version = $request->android_version;
                    $device->app_version     = $request->app_version;
                    $allSim                  = explode(',', $request->sim);

                    foreach ($allSim as $k => $sim) {
                        $simData[]    = [
                            'slot' => $k + 1,
                            'name' => @$sim,
                        ];
                    }
                    $device->sim = $simData;
                }

                $device->status = 1;
                $device->save();

                event(new DeviceAdd($device));

                $data        = $this->response();
                return apiResponse(true, 200, "Login Successfully", null, $data);
            }
        } catch (Exception $ex) {
            return  apiResponse(false, 500, null, [$ex->getMessage()]);
        }
    }


    public function logout(Request $request)
    {

        $validator    = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,device_id'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable Entity", $validator->errors()->all());
        }

        //disconnected device
        $device = Device::where('device_id', $request->device_id)->first();
        $device->status = 0;
        $device->save();

        DB::table('personal_access_tokens')->where('device_id', $device->device_id)->delete();
        event(new DeviceLogOut($device->device_id));
        return  apiResponse(true, 200, "Logout Successful");
    }

    protected function response()
    {
        $admin = auth()->guard('admin')->user();
        $data  = [
            'user'         => $admin,
            'access_token' => $admin->createToken('auth_token', request()->device_id)->plainTextToken,
            'token_type'   => 'Bearer',
            'base_url'     => route('home'),
            'pusher'       => [
                'pusher_key'     => config('app.PUSHER_APP_KEY'),
                'pusher_id'      => config('app.PUSHER_APP_ID'),
                'pusher_secret'  => config('app.PUSHER_APP_SECRET'),
                'pusher_cluster' => config('app.PUSHER_APP_CLUSTER'),
            ]
        ];
        return $data;
    }
}
