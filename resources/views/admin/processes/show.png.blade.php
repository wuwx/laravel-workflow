<?php
$graph = new Alom\Graphviz\Digraph('G');
foreach($process->places as $place) {
    $graph->node($place->name, ['label' => $place->title]);
}
foreach($process->transitions as $transition) {
    $graph->edge([$transition->froms, $transition->tos], ['label' => $transition->title]);
}

$process = new Symfony\Component\Process\Process("dot -Tpng");
$process->setInput($graph->render());
$process->run();
echo $process->getOutput();
