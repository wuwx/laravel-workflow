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
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;
        $transition_name = $request->transition_name;

        $transition = Transition::where('name', $transition_name)->first();

        $subject = app()->make($subject_type)->find($subject_id);
        //$subject = $subject->subjects()->first();

        $form = FormBuilder::plain();
        $current_user = $request->user();
        foreach($transition->attributes as $attribute) {
            $options = ExpressionLanguage::evaluate($attribute->options, compact('subject', 'current_user'));
            $form->add($attribute->name, $attribute->type, collect($options)->toArray());
        }

        return view('workflow::attributes.index', compact('form', 'transition'));
    }
}
