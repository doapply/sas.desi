<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sms;
use App\Events\MessageSend;
use App\Models\Device;

class CronController extends Controller
{

    public function sendSMS()
    {
        return $this->sms(0);
    }

    public function resendSMS()
    {
        return $this->sms(1);
    }

    protected function sms($et)
    {

        $currentTime  = now()->format('Y-m-d h:i');

        $gs          = gs();
        try {
            $sms  = Sms::whereIn('status', [0, 3])  //initial and schedule sms
                ->where('sms_type', 1)
                ->orderBy('id', 'ASC')
                ->where('et', $et)
                ->whereHas('device', function ($q) {
                    $q->where('status', 1);
                })
                ->select('id', 'message', 'device_slot_number', 'mobile_number', 'device_id')
                ->take(15)
                ->get();

            if ($et == 1) {
                info($sms->toArray());
            }

            foreach ($sms->groupBy('device_id') as $k =>  $newSMS) {
                event(new MessageSend([
                    'success'       => true,
                    'original_data' =>
                    [
                        'message'   => $newSMS->toArray(),
                        'device_id' => Device::where('id', $k)->first()->device_id
                    ]
                ]));
            }
            if ($et == 0) {
                Sms::where('status', 0)->where('schedule', '<=', $currentTime)
                    ->where('sms_type', 1)
                    ->whereHas('device', function ($q) {
                        $q->where('status', 1);
                    })
                    ->orderBy('id', 'ASC')
                    ->take(15)
                    ->where('et', 0)
                    ->update(['et' => 1]);
            }

            $gs->cron_error_message = null;
        } catch (Exception $ex) {
            $gs->cron_error_message = $ex->getMessage();
            info($ex->getMessage());
        }

        $gs->last_cron = now();
        $gs->save();

        return "EXECUTED";
    }
}
