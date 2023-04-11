<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;

class BatchController extends Controller
{
    public function smsBatch()
    {
        $pageTitle = 'Manage  Batch';
        $query     = Batch::with('sms:id,status,batch_id')->orderBy('id', 'DESC');
        $batches   = $query->paginate(getPaginate());
        return view('admin.batch.sms_batch', compact('pageTitle', 'batches'));
    }
}
