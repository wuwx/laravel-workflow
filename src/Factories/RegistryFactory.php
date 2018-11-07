<?php
namespace Wuwx\LaravelWorkflow\Factories;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\MarkingStore\MultipleStateMarkingStore;
use Symfony\Component\Workflow\Metadata\InMemoryMetadataStore;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Wuwx\LaravelWorkflow\Entities;
use Wuwx\LaravelWorkflow\Subscribers\WorkflowSubscriber;

class RegistryFactory
{
    static function make()
    {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber(new WorkflowSubscriber());

        $registry = new Registry();

        foreach(Entities\Workflow::all() as $workflow) {
            $name = $workflow->name;
            $workflowMetadata = $workflow->only('title');
            $placesMetadata = [];
            $transitionsMetadata = new \SplObjectStorage();

            $builder = tap(new DefinitionBuilder(), function ($builder) use ($workflow, &$placesMetadata, $transitionsMetadata) {
                foreach($workflow->places as $data) {
                    $place = $data->name;
                    $placesMetadata[$place] = ['title' => $data->title];
                    $builder->addPlace($place);
                }

                if ($initialPlace = array_get($workflow, 'initial_place')) {
                    $builder->setInitialPlace($initialPlace);
                }

                foreach($workflow->transitions as $data) {
                    $transition = new \Symfony\Component\Workflow\Transition($data->name, $data->froms, $data->tos);
                    $builder->addTransition($transition);
                    $transitionsMetadata[$transition] = [
                        'title'      => $data->title,
                        'attributes' => $data->attributes->all(),
                    ];
                }
            });

            $metadataStore = new InMemoryMetadataStore($workflowMetadata, $placesMetadata, $transitionsMetadata);
            $builder->setMetadataStore($metadataStore);

            $definition = $builder->build();
            $markingStoreArguments = array_get($workflow, 'marking_store.arguments', []);
            $markingStore = new SingleStateMarkingStore(...$markingStoreArguments);
            foreach(array_wrap($workflow->supports) as $support) {
                $registry->addWorkflow(new \Symfony\Component\Workflow\Workflow($definition, $markingStore, $eventDispatcher, $name), new InstanceOfSupportStrategy($support));
            }
        }

        foreach(app('config')->get('workflow.workflows', []) as $name => $workflow) {
            $workflowMetadata = array_get($workflow, 'metadata', []);
            $placesMetadata = [];
            $transitionsMetadata = new \SplObjectStorage();

            $builder = tap(new DefinitionBuilder(), function ($builder) use ($workflow, &$placesMetadata, $transitionsMetadata) {
                foreach($workflow['places'] as $name => $data) {
                    if (is_array($data)) {
                        $place = $name;
                        $placesMetadata[$place] = array_get($data, 'metadata');
                    } else {
                        $place = $data;
                    }
                    $builder->addPlace($place);
                }

                if ($initialPlace = array_get($workflow, 'initial_place')) {
                    $builder->setInitialPlace($initialPlace);
                }

                foreach($workflow['transitions'] as $name => $data) {
                    $transition = new \Symfony\Component\Workflow\Transition($name, $data['froms'], $data['tos']);
                    $builder->addTransition($transition);
                    $transitionsMetadata[$transition] = array_get($data, 'metadata', []);
                }
            });

            $metadataStore = new InMemoryMetadataStore($workflowMetadata, $placesMetadata, $transitionsMetadata);
            $builder->setMetadataStore($metadataStore);

            $definition = $builder->build();
            $markingStoreArguments = array_get($workflow, 'marking_store.arguments', []);
            switch (array_get($workflow, 'marking_store.type')) {
                case 'multiple_state':
                    $markingStore = new MultipleStateMarkingStore(...$markingStoreArguments);
                    break;
                case 'single_state':
                    $markingStore = new SingleStateMarkingStore(...$markingStoreArguments);
                    break;
                default:
                    $markingStore = new SingleStateMarkingStore(...$markingStoreArguments);
                    break;
            }

            foreach($workflow['supports'] as $support) {
                $registry->addWorkflow(new \Symfony\Component\Workflow\Workflow($definition, $markingStore, $eventDispatcher, $name), new InstanceOfSupportStrategy($support));
            }
        }

        return $registry;
    }
}
