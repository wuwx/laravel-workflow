<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;

class ProcessForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required',
        ]);

        $this->add('type', 'choice', [
            'choices' => [
                'main' => '主过程',
                'sub'  => '子过程',
            ],
            'rules' => 'required',
        ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
