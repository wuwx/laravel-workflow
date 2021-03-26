<?php

namespace Wuwx\LaravelWorkflow\Console;

use Illuminate\Console\Command;

use Wuwx\LaravelWorkflow\Factories\RegistryFactory;

class ListCommand extends Command
{

    protected $name = 'workflow:list';

    protected $description = '列出系统内所有工作流';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $registry = app('workflow.registry');

        $workflows = (function(){
            return $this->workflows;
        })->call($registry);

        $this->table(['name', 'title', 'supportStrategy', 'initial_places'], array_map(function($value) {
            list($workflow, $supportStrategy) = $value;
            return [
                $workflow->getName(),
                array_get($workflow->getMetadataStore()->getWorkflowMetadata(), 'title'),
                $supportStrategy->getClassName(),
                json_encode($workflow->getDefinition()->getInitialPlaces()),
            ];
        }, $workflows));
    }
}
