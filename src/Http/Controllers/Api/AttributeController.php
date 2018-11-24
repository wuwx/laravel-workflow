<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers\Api;

use ExpressionLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;
        $transitionName  = $request->transition_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);
        $workflow = app('workflow.registry')->get($subject, $workflow_name);

        foreach($workflow->getDefinition()->getTransitions() as $transition) {
            if ($transition->getName() == $transitionName) {
                break;
            }
        }

        $attributes = array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'attributes', []);
        $current_user = $request->user();
        return response()->json(collect($attributes)->map(function($attribute) use ($subject, $current_user) {
            $options = ExpressionLanguage::evaluate(array_get($attribute, 'options'), compact('subject', 'current_user'));
            return [
                'name' => $attribute['name'],
                'type' => $attribute['type'],
                'options' => $options,
            ];
        }));
    }
}
