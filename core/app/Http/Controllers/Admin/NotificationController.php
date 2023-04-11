<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function global()
    {
        $pageTitle = 'Global Template for Notification';
        return view('admin.notification.global_template', compact('pageTitle'));
    }

    public function globalUpdate(Request $request)
    {

        $request->validate([
            'email_from'     => 'required|email|string|max:40',
            'email_template' => 'required',
        ]);

        $general                 = GeneralSetting::first();
        $general->email_from     = $request->email_from;
        $general->email_template = $request->email_template;
        $general->save();

        $notify[] = ['success', 'Global notification settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function templates()
    {

        $pageTitle = 'Notification Templates';
        $templates = NotificationTemplate::orderBy('name')->get();
        return view('admin.notification.templates', compact('pageTitle', 'templates'));
    }

    public function templateEdit($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $pageTitle = $template->name;
        return view('admin.notification.edit', compact('pageTitle', 'template'));
    }

    public function templateUpdate(Request $request, $id)
    {
        $request->validate([
            'subject'    => 'required|string|max:255',
            'email_body' => 'required',
        ]);
        $template               = NotificationTemplate::findOrFail($id);
        $template->subj         = $request->subject;
        $template->email_body   = $request->email_body;
        $template->email_status = $request->email_status ? 1 : 0;
        $template->sms_status   = 0;
        $template->save();

        $notify[] = ['success', 'Notification template updated successfully'];
        return back()->withNotify($notify);
    }

    public function emailSetting()
    {
        $pageTitle = 'Email Notification Settings';
        return view('admin.notification.email_setting', compact('pageTitle'));
    }

    public function emailSettingUpdate(Request $request)
    {
        $request->validate([
            'email_method' => 'required|in:php,smtp,sendgrid,mailjet',
            'host'         => 'required_if:email_method,smtp',
            'port'         => 'required_if:email_method,smtp',
            'username'     => 'required_if:email_method,smtp',
            'password'     => 'required_if:email_method,smtp',
            'enc'          => 'required_if:email_method,smtp',
            'appkey'       => 'required_if:email_method,sendgrid',
            'public_key'   => 'required_if:email_method,mailjet',
            'secret_key'   => 'required_if:email_method,mailjet',
        ], [
            'host.required_if'       => ':attribute is required for SMTP configuration',
            'port.required_if'       => ':attribute is required for SMTP configuration',
            'username.required_if'   => ':attribute is required for SMTP configuration',
            'password.required_if'   => ':attribute is required for SMTP configuration',
            'enc.required_if'        => ':attribute is required for SMTP configuration',
            'appkey.required_if'     => ':attribute is required for SendGrid configuration',
            'public_key.required_if' => ':attribute is required for Mailjet configuration',
            'secret_key.required_if' => ':attribute is required for Mailjet configuration',
        ]);

        if ($request->email_method == 'php') {
            $data['name'] = 'php';
        } else if ($request->email_method == 'smtp') {
            $request->merge(['name' => 'smtp']);
            $data = $request->only('name', 'host', 'port', 'enc', 'username', 'password', 'driver');
        } else if ($request->email_method == 'sendgrid') {
            $request->merge(['name' => 'sendgrid']);
            $data = $request->only('name', 'appkey');
        } else if ($request->email_method == 'mailjet') {
            $request->merge(['name' => 'mailjet']);
            $data = $request->only('name', 'public_key', 'secret_key');
        }

        $general              = GeneralSetting::first();
        $general->mail_config = $data;
        $general->save();
        $notify[] = ['success', 'Email settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function emailTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $general      = GeneralSetting::first();
        $config       = $general->mail_config;
        $receiverName = explode('@', $request->email)[0];
        $subject      = strtoupper($config->name) . ' Configuration Success';
        $message      = 'Your email notification setting is configured successfully for ' . $general->site_name;

        if ($general->en) {
            $user = [
                'username' => $request->email,
                'email'    => $request->email,
                'fullname' => $receiverName,
            ];
            notify($user, 'DEFAULT', [
                'subject' => $subject,
                'message' => $message,
            ], ['email']);
        } else {
            $notify[] = ['info', 'Please enable from general settings'];
            $notify[] = ['error', 'Your email notification is disabled'];
            return back()->withNotify($notify);
        }

        if (session('mail_error')) {
            $notify[] = ['error', session('mail_error')];
        } else {
            $notify[] = ['success', 'Email sent to ' . $request->email . ' successfully'];
        }

        return back()->withNotify($notify);
    }
}
