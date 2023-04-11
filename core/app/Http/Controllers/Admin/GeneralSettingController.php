<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Image;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $pageTitle = 'General Setting';
        $timezones = json_decode(file_get_contents(resource_path('views/admin/partials/timezone.json')));
        return view('admin.setting.general', compact('pageTitle', 'timezones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'          => 'required|string|max:40',
            'timezone'           => 'required',
            'pusher_app_id'      => 'required',
            'pusher_app_key'     => 'required',
            'pusher_app_secret'  => 'required',
            'pusher_app_cluster' => 'required',
            'batch_id_prefix'    => 'required',
        ]);

        $general                  = GeneralSetting::first();
        $general->en              = $request->en ? 1 : 0;
        $general->sn              = $request->sn ? 1 : 0;
        $general->site_name       = $request->site_name;
        $general->batch_id_prefix = $request->batch_id_prefix;
        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = ' . $request->timezone . ' ?>';
        file_put_contents($timezoneFile, $content);

        //pusher config
        $pusherConfigFile = config_path('pusher.php');

        $pusherConfig = '<?php' . PHP_EOL .
            '$pusherAppId       = ' . '"' . $request->pusher_app_id . '"' . ";" . PHP_EOL .
            '$pusherAppKey      =' . '"' . $request->pusher_app_key . '"' . ";" . PHP_EOL .
            '$pusherAppSecret   =' . '"' . $request->pusher_app_secret . '"' . ';' . PHP_EOL .
            '$pusherAppCluster  =' . '"' . $request->pusher_app_cluster . '"' . ';' . PHP_EOL .
            "?>";

        file_put_contents($pusherConfigFile,  $pusherConfig);
        
        $notify[] = ['success', 'General setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo'    => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon' => ['image', new FileTypeValidate(['png'])],
        ]);

        if ($request->hasFile('logo')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', getFileSize('favicon'));
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon updated successfully'];
        return back()->withNotify($notify);
    }
}
