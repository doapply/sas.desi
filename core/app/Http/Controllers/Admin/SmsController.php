<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Sms;
use App\Models\Group;
use App\Models\Device;
use App\Models\Contact;
use App\Models\Template;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    public function index()
    {
        $pageTitle = 'All SMS';
        $messages  = Sms::filter(['mobile_number', 'sms_type', 'status', 'device_id'])->dateFilter()->with('device','failReason')->orderBy('id', 'desc')->paginate(getPaginate());
        $allDevice = Device::orderBy('id', 'DESC')->get();
        return view('admin.sms.index', compact('pageTitle', 'messages', 'allDevice'));
    }

    public function send()
    {
        $pageTitle    = 'Send SMS';
        $allDevice    = Device::connected()->orderBy('id', 'DESC')->get();
        $templates    = Template::active()->orderBy('id', 'DESC')->get();
        $groups       = Group::active()->orderBy('name', 'ASC')->get();
        $contactExits = Contact::exists();
        return view('admin.sms.send', compact('pageTitle', 'templates', 'allDevice', 'groups', 'contactExits'));
    }

    public function sendSMS(Request  $request)
    {

        $validator = $this->validation($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->all()
            ]);
        }
        $numberFromFile = [];

        if ($request->hasFile('file')) {
            try {
                $fileRead       = importFileReader($request->file, ['mobile'], [], false);
                $numberFromFile = array_column($fileRead->getReadData(), 0);
            } catch (Exception $ex) {
                return response()->json([
                    'success' => false,
                    'errors'  => $ex->getMessage()
                ]);
            }
        }

        if ($request->select_all_group) {
            $numberFromGroup  = GroupContact::with('contact', function ($query) {
                $query->where('status', 1);
            })->get()->pluck('contact.mobile')->toArray();
        } else {
            $numberFromGroup  = GroupContact::whereIn('group_id', $request->group ?? [])->with('contact', function ($query) {
                $query->where('status', 1);
            })->get()->pluck('contact.mobile')->toArray();
        }

        $mobileNumbers     = explode(',', $request->mobile_numbers);

        if ($request->select_all_contact) {
            $numberFromContact = Contact::where('status', 1)->select('mobile')->pluck('mobile')->toArray();
        } else {
            $numberFromContact = Contact::where('status', 1)->whereIn('id', $request->contact_list ?? [])->select('mobile')->pluck('mobile')->toArray();
        }

        $numbers           = array_merge($mobileNumbers, $numberFromFile, $numberFromGroup, $numberFromContact);
        $numbers           = array_map('trim', $numbers);
        $numbers           = array_unique(array_filter($numbers));

        if (count($numbers) <= 0) {
            return response()->json([
                'success' => false,
                'message' => "At least one mobile number required"
            ]);
        }

        $deviceSlot = explode('***', $request->sim);

        if ($request->date) {
            $status = 3;   //schedule sms
            $schedule = Carbon::parse($request->date)->format('Y-m-d h:i');
        } else {
            $status = 0;
            $schedule = now()->format('Y-m-d h:i');
        }
        $device     = Device::where('id', $request->device)->first();
        $now        = now();
        $batch      = createBatch();
        $message    = strip_tags($request->message);

        foreach ($numbers as $number) {
            $data = [
                'device_id'          => $device->id,
                'device_slot_number' => $deviceSlot[0],
                'device_slot_name'   => $deviceSlot[1],
                'mobile_number'      => $number,
                'message'            => $message,
                'schedule'           => $schedule,
                'batch_id'           => $batch->id,
                'created_at'         => $now,
                'updated_at'         => $now,
                'status'             => $status
            ];
            Sms::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => count($numbers) . ' Sms should be send'
        ]);
    }

    protected function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'message'      => 'required|string',
            'schedule'     => 'required|in:1,2',                                                                        //schedule 1=now,2=future date
            'date'         => "required_if:schedule,==,2|nullable|date|date_format:Y-m-d h:i a|after_or_equal:today",
            'sim'          => 'required',
            'device'       => 'required|integer|exists:devices,id',
            'file'         => ['nullable', 'file', new FileTypeValidate(['csv', 'xlsx', 'txt'])],
            'group'        => 'nullable|array',
            'contact_list' => 'nullable|array'
        ], [
            "date.required_if"    => "The date filed is required",
            "date.after_or_equal" => "The date must be today or future date",
            "date.date_format"    => "The date format invalid",
        ]);

        return $validator;
    }
}
