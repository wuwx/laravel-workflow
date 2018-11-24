<?php

namespace Wuwx\LaravelWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;
use Wuwx\LaravelWorkflow\Entities\Transition;

class ApplyController extends Controller
{
    public function __invoke(Request $request)
    {
        #TODO: 最好放在一个事务里，否则有问题
        $subject_id     = $request->subject_id;
        $subject_type   = $request->subject_type;
        $workflow_name  = $request->workflow_name;
        $transitionName = $request->transition_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);
        #TODO: 还需要针对版本
        $workflow = app('workflow.registry')->get($subject, $workflow_name);

        try {
            #TODO: 应该先保存属性再保存工作流
            $workflow->apply($subject, $transitionName);
            $subject->save();

            foreach($workflow->getDefinition()->getTransitions() as $transition) {
                if ($transition->getName() == $transitionName) {
                    break;
                }
            }

            foreach(array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'attributes', []) as $attribute) {
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

        } catch (\Symfony\Component\Workflow\Exception\InvalidArgumentException $exception) {

        }

        return redirect()->back();
    }
}
