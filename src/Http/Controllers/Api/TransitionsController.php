<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TransitionsController extends Controller
{
    public function index(Request $request)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);

        $workflow = app('workflow.registry')->get($subject, $workflow_name);
        $transitions = collect($workflow->getEnabledTransitions($subject));

        return response()->json($transitions->map(function($transition) use ($workflow) {
            return [
                'name' => $transition->getName(),
                'title' => array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'title'),
            ];
        }));
    }
}
