<?php

namespace Wuwx\LaravelWorkflow\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DumpCommand extends Command
{
    protected $name = 'workflow:dump';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $registry = app('workflow.registry');

        $workflows = (function(){
            return $this->workflows;
        })->call($registry);

        foreach($workflows as list($workflow, $supportStrategy)) {
            if ($workflow->getName() == $name) {
                $graph = new Alom\Graphviz\Digraph('G');
                foreach($workflow->places as $place) {
                    $graph->node($place->name, ['label' => $place->title]);
                }
                foreach($workflow->transitions as $transition) {
                    $graph->edge([$transition->froms, $transition->tos], ['label' => $transition->title]);
                }

                $process = new Symfony\Component\Process\Process("dot -Tpng");
                $process->setInput($graph->render());
                $process->run();
                echo $process->getOutput();
                break;
            }
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, ''],
        ];
    }
}
