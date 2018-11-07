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
        $subject_id      = $request->subject_id;
        $subject_type    = $request->subject_type;
        $workflow_name   = $request->workflow_name;
        $transition_name = $request->transition_name;

        #TODO: fixit later;
        $subject = app()->make($subject_type)->find($subject_id);
        #TODO: 还需要针对版本
        $transition = Transition::whereName($transition_name)->first();

        try {
            #TODO: 应该先保存属性再保存工作流
            Workflow::get($subject, $workflow_name)->apply($subject, $transition_name);
            $subject->save();

            foreach(collect($transition->attributes) as $attribute) {
                if (array_has($subject->getAttributes(), $attribute->name)) {
                    if ($attribute->type == 'file') {
                        if ($request->hasFile($attribute->name)) {
                            $subject->{$attribute->name} = $request->file($attribute->name)->store('workflow', 'public');
                        }
                    } else {
                        $subject->{$attribute->name} = $request->input($attribute->name);
                    }
                }
            }
            $subject->save();

        } catch (\Symfony\Component\Workflow\Exception\InvalidArgumentException $exception) {

        }

        return redirect()->back();
    }
}
