<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;

class VersionForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'rules' => 'required',
        ]);

        $this->add('status', 'choice', [
            'choices' => [
                'draft' => 'draft',
                'publish' => 'publish',
            ]
        ]);

        $this->add('submit', 'submit', [
            'label' => '提交',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
    }
}
