# laravel-workflow

```php
<?php
return [
    'workflows' => [
        'process_1' => [
            'places' => ['initial', 'place_1', 'place_2'],
            'transitions' => [
                'transition_1' => [
                    'froms' => ['initial'], 'tos' => ['place_1'],
                ],
                'transition_2' => [
                    'froms' => ['place_1'], 'tos' => ['place_2'],
                ],
            ],
        ],
     ],
];
```

```php
Gate::define('viewWorkflow', function ($user) {
    return true;
});
```