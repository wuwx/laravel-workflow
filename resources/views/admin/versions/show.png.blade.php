<?php
$graph = new Alom\Graphviz\Digraph('G');
foreach($version->processes as $process) {
    $subgraph = $graph->subgraph("cluster_" . $process->id);
    $subgraph->set('label', $process->title);
    foreach($process->places as $place) {
        $subgraph->node($place->name, ['label' => $place->title]);

        foreach ($place->processes as $subProcess) {
            $graph->edge([$place->name, $subProcess->places()->whereInitial(true)->first()->name]);
        }

        $subgraph->node($place->name, ['label' => $place->title]);
    }
    foreach($process->transitions as $transition) {
        $subgraph->edge([$transition->froms, $transition->tos], ['label' => $transition->title]);
    }
}

$process = new Symfony\Component\Process\Process("dot -Tpng");
$process->setInput($graph->render());
$process->run();
echo $process->getOutput();
