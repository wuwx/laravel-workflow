<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Api;

use ExpressionLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class StepController extends Controller
{
    public function index(Request $request)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);
        $workflow = app('workflow.registry')->get($subject, $workflow_name);

        $steps = $workflow->getMetadataStore()->getMetadata('steps');
        $current = array_get($workflow->getMetadataStore()->getPlaceMetadata(key($workflow->getMarking($subject)->getPlaces())), 'step');

        return response()->json(compact('steps', 'current'));
    }
}
