<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);

        $workflow = app('workflow.registry')->get($subject, $workflow_name);
        $histories = $subject->histories;

        return response()->json($histories);
    }
}
