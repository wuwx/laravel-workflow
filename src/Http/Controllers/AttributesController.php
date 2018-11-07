<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use ExpressionLanguage;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use App\School;
use App\Issue;
use Wuwx\LaravelWorkflow\Entities\Transition;
use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;

class AttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $subject_id     = $request->subject_id;
        $subject_type   = $request->subject_type;
        $workflow_name  = $request->workflow_name;
        $transitionName = $request->transition_name;

        $subject = app()->make($subject_type)->find($subject_id);
        $workflow = app('workflow.registry')->get($subject, $workflow_name);

        foreach($workflow->getDefinition()->getTransitions() as $transition) {
            if ($transition->getName() == $transitionName) {
                break;
            }
        }

        $subject = app()->make($subject_type)->find($subject_id);
        //$subject = $subject->subjects()->first();

        $form = FormBuilder::plain();
        $current_user = $request->user();
        $attributes = array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'attributes', []);
        foreach($attributes as $attribute) {
            $options = ExpressionLanguage::evaluate($attribute->options, compact('subject', 'current_user'));
            $form->add($attribute->name, $attribute->type, collect($options)->toArray());
        }

        return view('workflow::attributes.index', compact('form', 'attributes'));
    }
}
