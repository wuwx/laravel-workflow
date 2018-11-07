<?php

namespace Wuwx\LaravelWorkflow\Facades;

use Illuminate\Support\Facades\Facade;

class RegistryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'workflow.registry';
    }
}
