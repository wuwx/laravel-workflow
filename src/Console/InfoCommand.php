<?php

namespace Wuwx\LaravelWorkflow\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Wuwx\LaravelWorkflow\Factories\RegistryFactory;
use Wuwx\LaravelWorkflow\Entities\Subject;

class InfoCommand extends Command
{

    protected $name = 'workflow:info';

    protected $description = '显示某个 Workflow 更多信息';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $registry = RegistryFactory::make();
        $workflow = $registry->get(new Subject(), $name);

        $this->table(['name', 'title'], array_map(function($place) use ($workflow) {
            return [
                $place,
                array_get($workflow->getMetadataStore()->getPlaceMetadata($place), 'title'),
            ];
        }, $workflow->getDefinition()->getPlaces()));

        $this->table(['name', 'title', 'froms', 'tos'], array_map(function($transition) use ($workflow) {
            return [
                $transition->getName(),
                array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'title'),
                json_encode($transition->getFroms()),
                json_encode($transition->getTos())
            ];
        }, $workflow->getDefinition()->getTransitions()));
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, ''],
        ];
    }
}
