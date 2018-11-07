<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ExpressionLanguage;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use App\School;
use App\Issue;
use Wuwx\LaravelWorkflow\Entities\Transition;
use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;

class TransitionsController extends Controller
{
    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request, Transition $transition)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $subject = app()->make($subject_type)->find($subject_id);
        $subject = $subject->subjects()->first();

        $form = FormBuilder::plain();
        $current_user = $request->user();
        foreach($transition->attributes as $attribute) {
            $options = ExpressionLanguage::evaluate($attribute->options, compact('subject', 'current_user'));
            $form->add($attribute->name, $attribute->type, collect($options)->toArray());
        }

        return view('workflow::transitions.show', compact('school', 'issue', 'transition', 'form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, Transition $transition)
    {
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $subject = app()->make($subject_type)->find($subject_id);
        $subject = $subject->subjects()->first();

        Workflow::get($subject, $subject->process->name)->apply($subject, $request->transition);
        $subject->save();

        foreach(collect($transition->attributes) as $attribute) {
            if (array_has($subject->entity->getAttributes(), $attribute->name)) {
                $subject->entity->{$attribute->name} = $request->input($attribute->name);
            }
        }
        $subject->entity->save();
        return redirect()->back();
    }

}
