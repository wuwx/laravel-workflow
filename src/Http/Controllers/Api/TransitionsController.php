<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Wuwx\LaravelWorkflow\Entities\Workflow;

class TransitionsController extends Controller
{
    public function index(Request $request)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);

        $transitions = collect(app('workflow.registry')->get($subject, $workflow_name)->getEnabledTransitions($subject))->map(function($transition) use ($workflow_name){
            $workflow = Workflow::whereName($workflow_name)->first();
            $transition = $workflow->transitions()->whereName($transition->getName())->first();

            return [
                'name' => $transition->name,
                'title' => $transition->title,
            ];
        });
        return response()->json($transitions);
    }
}
