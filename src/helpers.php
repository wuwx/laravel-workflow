<?php
use Wuwx\LaravelWorkflow\Facades\RegistryFacade as Workflow;

if (! function_exists('workflow_can')) {
    function workflow_can($subject, $transitionName, $name = null) {
        return Workflow::get($subject, $name)->can($subject, $transitionName);
    }
}

if (! function_exists('workflow_transitions')) {
    function workflow_transitions($subject, $name = null) {
        return Workflow::get($subject, $name)->getEnabledTransitions($subject);
    }
}

if (! function_exists('workflow_marked_places')) {
    function workflow_marked_places($subject, $placesNameOnly = true, $name = null) {
        $places = Workflow::get($subject, $name)->getMarking($subject)->getPlaces();

        if ($placesNameOnly) {
            return array_keys($places);
        }

        return $places;
    }
}

if (! function_exists('workflow_has_marked_place')) {
    function workflow_has_marked_place($subject, $placeName, $name = null) {
        return Workflow::get($subject, $name)->getMarking($subject)->has($placeName);
    }
}


if (! function_exists('workflow_metadata')) {
    function workflow_metadata($subject, string $key, $metadataSubject = null, string $name = null) {
        return Workflow::get($subject, $name)->getMetadataStore()->getMetadata($key, $metadataSubject);
    }
}
