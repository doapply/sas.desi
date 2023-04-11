<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Group;
use App\Models\Contact;
use App\Models\GroupContact;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    public function index()
    {
        $groups    = Group::filter(['status'])->withCount('contact as total_contact')->paginate(getPaginate());
        $pageTitle = "Manage Group";
        return view('admin.group.index', compact('pageTitle', 'groups'));
    }

    public function saveGroup(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|unique:groups,name,' . $id,
        ]);

        if ($id) {
            $group         = Group::findOrFail($id);
            $message       = "Group updated successfully";
        } else {
            $group   = new Group();
            $message = "Group added successfully";
        }

        $group->name = $request->name;
        $group->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function viewGroupContact($id)
    {
        $group     = Group::where('id', $id)->firstOrFail();
        $pageTitle = 'View Group: ' . $group->name;
        $contacts  = GroupContact::where('group_id', $id)->orderBy('id', 'DESC')->with('contact')->paginate(getPaginate());
        return view('admin.group.view_contact', compact('pageTitle', 'group', 'contacts'));
    }

    public function contactSaveToGroup(Request $request, $groupId)
    {
        $request->validate([
            'contacts'   => 'required|array',
            'contacts.*' => 'required|integer|exists:contacts,id',
        ]);

        $group = Group::where('id', $groupId)->firstOrFail();

        if (!$group->status) {
            $notify[] = ['error', 'Currently Group is inactive'];
            return back()->withNotify($notify);
        }

        $contactId = [];

        foreach ($request->contacts as $contact) {
            $groupContact = GroupContact::where('group_id', $request->group_id)->where('contact_id', $contact)->exists();
            if (!$groupContact) {
                $contactId[] = $contact;
            }
        }
        $group->contact()->attach($contactId);

        $notify[] = ['success', "Contact added successfully"];
        return back()->withNotify($notify);
    }

    public function deleteContactFromGroup($id)
    {
        GroupContact::where('id', $id)->delete();
        $notify[]     = ['success', "Contact successfully removed"];
        return back()->withNotify($notify);
    }

    public function importContactToGroup(Request $request, $groupId)
    {

        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', new FileTypeValidate(['csv', 'xlsx', 'txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $group = Group::where('id', $groupId)->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => "Group not found"
            ]);
        }

        if (!$group->status) {
            return response()->json([
                'success' => false,
                'message' => "Currently Group is inactive"
            ]);
        }

        $columnNames = ['mobile'];
        $contactId   = [];
        $collection  = [];

        try {
            $fileReadData = importFileReader($request->file, $columnNames, $columnNames);
            if (count($fileReadData->allData)) {
                foreach ($fileReadData->allData as $item) {
                    $collection[] = @$item[0];
                }
            }
            $columnName            = implode(',', $columnNames);
            $contactId             = Contact::where('status', 1)->whereIn($columnName, $collection)->select('id')->pluck('id')->toArray();
            $alreadyExistContactId = GroupContact::where('group_id', $group->id)->pluck('contact_id')->toArray();
            $newContactId          = array_diff($contactId, $alreadyExistContactId);

            if (count($newContactId) > 0) {
                $group->contact()->attach($newContactId);
            }
            $notify['success'] = true;
            $notify['message'] = count($newContactId) . " contacts added to group";

        } catch (Exception $ex) {
            $notify['success'] = false;
            $notify['message'] = $ex->getMessage();
        }
        return response()->json($notify);
    }
    
    public function status($id)
    {
        $group         = Group::findOrFail($id);
        if($group->status==1){
            $group->status=0;
            $message="Group inactive successfully";
        }else{
            $group->status=1;
            $message="Group active successfully";
        }
        $group->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
