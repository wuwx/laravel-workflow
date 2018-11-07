<?php

namespace Wuwx\LaravelWorkflow\Forms;

use Kris\LaravelFormBuilder\Form;

class AttributeForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden');
        $this->add('name', 'text');
        $this->add('type', 'select', [
            'choices' => [
                'file'     => 'file',
                'hidden'   => 'hidden',
                'select'   => 'select',
                'static'   => 'static',
                'text'     => 'text',
                'textarea' => 'textarea',

            ]
        ]);
        $this->add('options', 'textarea', [
            'attr' => [
                'rows' => "3",
            ],
        ]);
    }
}
