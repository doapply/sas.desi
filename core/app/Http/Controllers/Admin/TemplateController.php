<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{

    public function index()
    {
        $pageTitle = 'Manage Template';
        $templates = Template::latest()->filter(['status'])->paginate(getPaginate());
        return view('admin.template.index', compact('pageTitle', 'templates'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name'    => 'required|unique:templates,name,' . $id,
            'message' => 'required|string',
        ]);

        if ($id) {
            $template         = Template::findOrFail($id);
            $template->status = $request->status ? 1 : 0;
            $message          = "Template updated successfully";
        } else {
            $template = new Template();
            $message  = "Template added successfully";
        }

        $template->name    = $request->name;
        $template->message = $request->message;
        $template->save();
        
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
    
    public function status($id)
    {
        $template         = Template::findOrFail($id);
        if($template->status==1){
            $template->status=0;
            $message="Template inactive successfully";
        }else{
            $template->status=1;
            $message="Template active successfully";
        }
        $template->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
