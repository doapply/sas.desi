<?php

namespace App\Http\Controllers\Api\Admin;

use Carbon\Carbon;
use App\Models\Device;
use App\Models\Sms;
use Illuminate\Http\Request;
use App\Events\MessageReceived;
use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{

    public function update(Request $request, $id)
    {

        $validator   = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3,4,9'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable entity", $validator->errors()->all());
        }
        $sms = Sms::where('id', $id)->whereNotIn('status', [1, 9])->first();

        if (!$sms) {
            return apiResponse(false, 406, null, ["SMS Not Found"]);
        }


        $sms->status = $request->status;
        $sms->error_code = $request->error_code ?? 0;
        $sms->save();
        return apiResponse(true, 200, "SMS updated successfully -- message_id: $id");
    }

    public function received(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'device_id'          => 'required',
            'device_slot_number' => 'required|integer|in:1,2',
            'device_slot_name'   => 'required',
            'mobile_number'      => 'required',
            'message'            => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable entity", $validator->errors()->all());
        }

        $device = Device::where('device_id', $request->device_id)->first();

        if (!$device) {
            return apiResponse(false, 406, null, ["Device Not Found"]);
        }

        $sms                     = new Sms();
        $sms->device_id          = $device->id;
        $sms->device_slot_number = $request->device_slot_number;
        $sms->device_slot_name   = $request->device_slot_name;
        $sms->mobile_number      = $request->mobile_number;
        $sms->schedule           = now()->format('Y-m-d h:i');
        $sms->message            = $request->message;
        $sms->status             = 1;
        $sms->sms_type           = 2;                             //sms_type 2= Sms Received
        $sms->save();

        $sms->device_name        = $device->device_name . '-' . $device->device_model;

        $data['message'] = $sms;
        $response       = apiResponse(true, 200, "Sms Received Successfully", [], $data);

        event(new MessageReceived($response));
        return $response;
    }

    public function send(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable Entity", $validator->errors()->all());
        }

        $schedule = $request->date ? Carbon::parse($request->date)->format('Y-m-d h:i') : now()->format('Y-m-d h:i');

        $device = Device::where('device_id', $request->device)->first();

        if (!$device->status) {
            return apiResponse(false, 424, null, ['Device not connected']);
        }

        $slotNumber = $request->device_sim && $request->device_sim == 2 ? 1 : 0;
        $deviceSlot = @$device->sim[$slotNumber];

        if (gettype($deviceSlot) != 'array') {
            return apiResponse(false, 424, null, ['Error found for device SIM slot']);
        }

        $now           = now();
        $batch         = createBatch();
        $numbers       = explode(',', $request->mobile_number);
        $numbers       = array_unique(array_filter($numbers));
        $apiKey         = ApiKey::where('status', 1)->where('key', $request->header()['apikey'][0])->first();

        foreach ($numbers as $number) {
            $data = [
                'device_id'          => $device->id,
                'device_slot_number' => $deviceSlot['slot'],
                'device_slot_name'   => $deviceSlot['name'],
                'mobile_number'      => $number,
                'message'            => strip_tags($request->message),
                'schedule'           => $schedule,
                'batch_id'           => $batch->id,
                'created_at'         => $now,
                'updated_at'         => $now,
                'api_key_id'         => $apiKey->id
            ];
            Sms::create($data);
        }
        return apiResponse(true, 200, count($numbers) . ' Sms should be send');
    }

    public function sendViaGet(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return apiResponse(false, 422, "Unprocessable Entity", $validator->errors()->all());
        }

        $device = Device::where('status', 1)->where('device_id', $request->device)->first();

        if (!$device->status) {
            return apiResponse(false, 424, null, ['Device not connected']);
        }

        $deviceSlot = @$device->sim[0];

        if (gettype($deviceSlot) != 'array') {
            return apiResponse(false, 424, null, ['Error found for device SIM slot']);
        }

        $batch  = createBatch();
        $now    = now();
        $apiKey = ApiKey::where('status', 1)->where('key', $request->apikey)->first();

        $sms                     = new Sms();
        $sms->device_id          = $device->id;
        $sms->device_slot_number = $deviceSlot['slot'];
        $sms->device_slot_name   = $deviceSlot['name'];
        $sms->mobile_number      = $request->mobile_number;
        $sms->message            = strip_tags($request->message);
        $sms->schedule           = $now;
        $sms->batch_id           = $batch->id;
        $sms->api_key_id         = $apiKey->id;
        $sms->save();

        return apiResponse(true, 200, '1 Sms should be send');
    }

    protected function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'message'       => 'required|string|max:160',
            'date'          => "nullable|date|date_format:Y-m-d h:i a|after_or_equal:today",
            'device'        => 'required|exists:devices,device_id',
            'mobile_number' => 'required',
            'device_sim'    => 'nullable|in:1,2',
        ], [
            "date.after_or_equal" => "The date must be today or future date",
            "date.date_format"    => "The date format invalid",
            "message.max"         => "The message 160 character  allowed",
        ]);

        return $validator;
    }
}
