<?php
$graph = new Alom\Graphviz\Digraph('G');
foreach($workflow->getDefinition()->getPlaces() as $place) {
    $graph->node($place, ['label' => array_get($workflow->getMetadataStore()->getPlaceMetadata($place), 'title')]);
}
foreach($workflow->getDefinition()->getTransitions() as $transition) {
    $graph->edge([$transition->getFroms(), $transition->getTos()], ['label' => array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'title')]);
}

$process = new Symfony\Component\Process\Process("dot -Tpng");
$process->setInput($graph->render());
$process->run();
echo $process->getOutput();
