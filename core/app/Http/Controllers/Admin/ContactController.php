<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public function index()
    {
        $pageTitle = 'Manage Contacts';
        $contacts  = Contact::filter(['mobile', 'status'])->orderBy('id', 'DESC')->paginate(getPaginate());
        $columns   = Contact::getColumNames();                                                               //for filter colum when export data
        return view('admin.contact.index', compact('pageTitle', 'contacts', 'columns'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'mobile'        => "required|unique:contacts,mobile," . $id,
        ]);

        if ($id) {
            $contact         = Contact::findOrFail($id);
            $message         = 'Contact updated successfully';
        } else {
            $message = 'New contact added successfully';
            $contact = new Contact();
        }

        $contact->mobile = $request->mobile;
        $contact->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
    public function status($id)
    {
        $contact         = Contact::findOrFail($id);
        if($contact->status==1){
            $contact->status=0;
            $message="Contact inactive successfully";
        }else{
             $contact->status=1;
            $message="Contact active successfully";
        }
        $contact->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function importContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:3072', new FileTypeValidate(['csv', 'xlsx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Validation Error",
                'errors'  => $validator->errors()->all()
            ]);
        }

        $columnNames = ['mobile'];  //column & unique column name
        $notify      = [];
        try {
            $import = importFileReader($request->file, $columnNames, $columnNames); //column & unique column name are same
            $notify = $import->notifyMessage();
        } catch (Exception $ex) {
            $notify['success'] = false;
            $notify['message'] = $ex->getMessage();
        }
        return response()->json($notify);
    }

    public  function exportContact(Request $request)
    {
        $request->validate([
            'columns'     => 'required|array',
            'export_item' => 'required|integer',
            'order_by'    => 'required|in:ASC,DESC',
        ]);

        $contact                = new Contact();
        $contact->exportColumns = $request->columns;
        $contact->fileName      = 'contact.csv';
        $contact->exportItem    = $request->export_item;
        $contact->orderBy       = $request->order_by;

        return  $contact->export();
    }

    public function contactSearch()
    {

        $query = Contact::active();

        if (request()->search) {
            $query->where('mobile', "Like", "%" . request()->search . "%");
        }
        if (request()->group_id) {
            $query->whereDoesntHave('groupContact', function ($q) {
                $q->where('group_id', request()->group_id);
            });
        }
        $contacts = $query->paginate(getPaginate());

        return response()->json([
            'success'  => true,
            'contacts' => $contacts,
            'more'     => $contacts->hasMorePages()
        ]);
    }
}
