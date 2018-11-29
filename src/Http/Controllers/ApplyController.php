<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers;

use ExpressionLanguage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;
use Wuwx\LaravelWorkflow\Entities\Transition;

class ApplyController extends Controller
{
    public function __invoke(Request $request)
    {
        $current_user   = $request->user();
        $subject_id     = $request->subject_id;
        $subject_type   = $request->subject_type;
        $workflow_name  = $request->workflow_name;
        $transitionName = $request->transition_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);
        #TODO: 还需要针对版本
        $workflow = app('workflow.registry')->get($subject, $workflow_name);

        try {
            DB::beginTransaction();

            foreach($workflow->getDefinition()->getTransitions() as $transition) {
                if ($transition->getName() == $transitionName) {
                    break;
                }
            }

            $workflow->apply($subject, $transitionName);

            foreach(array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'attributes', []) as $attribute) {
                $options = ExpressionLanguage::evaluate(array_get($attribute, 'options'), compact('subject', 'current_user'));
                $request->validate([$attribute['name'] => array_get($options, 'rules')]);

                if (array_has($subject->getAttributes(), array_get($attribute, 'name'))) {
                    if (array_get($attribute, 'type') == 'file') {
                        if ($request->hasFile(array_get($attribute, 'name'))) {
                            $subject->{array_get($attribute, 'name')} = $request->file(array_get($attribute, 'name'))->store('workflow', 'public');
                        }
                    } else {
                        $subject->{array_get($attribute, 'name')} = $request->input(array_get($attribute, 'name'));
                    }
                }
            }
            $subject->save();

            DB::commit();

        } catch (\Symfony\Component\Workflow\Exception\InvalidArgumentException $exception) {

        }

        return redirect()->back();
    }
}
