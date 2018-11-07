<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;
use Silber\Bouncer\Database\Role;

class TransitionForm extends Form
{

    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required',
        ]);

        $this->add('description', 'text', [
            'label' => '描述',
        ]);

        $this->add('froms', 'choice', [
            'wrapper' => ['class' => 'form-group col-md-6'],
            'choices' => $this->getData('workflow')->places()->pluck('title', 'name')->toArray(),
            'multiple' => true,
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('tos', 'choice', [
            'wrapper' => ['class' => 'form-group col-md-6'],
            'choices' => $this->getData('workflow')->places()->pluck('title', 'name')->toArray(),
            'multiple' => true,
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('role_ids', 'choice', [
            'choices' => Role::pluck('title', 'id')->all(),
            'choice_options' => [
                'wrapper' => ['class' => ''],
            ],
            'multiple' => true,
            'attr' => ['class' => "form-control select2"],
        ]);

        $this->add('guard', 'textarea', [
            'label' => '保护',
            'attr' => [
                'rows' => "3",
            ],
            'help_block' => [
                'text' => "若表达式为真，则不允许执行",
            ],
        ]);

        $this->add('automatic', 'textarea', [
            'label' => '自动执行',
            'attr' => [
                'rows' => "3",
            ],
            'help_block' => [
                'text' => "若表达式为真，自动执行动作",
            ],
        ]);

        // $this->add('attributes', 'collection', [
        //     'type' => 'form',
        //     'options' => [
        //         'class' => AttributeForm::class,
        //         'wrapper' => ['class' => 'form-group row'],
        //         'label' => false,
        //     ],
        // ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
