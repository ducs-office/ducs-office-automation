<?php return [
    'beyondcode/laravel-er-diagram-generator' => [
        'providers' => [
            0 => 'BeyondCode\\ErdGenerator\\ErdGeneratorServiceProvider',
        ],
    ],
    'facade/ignition' => [
        'providers' => [
            0 => 'Facade\\Ignition\\IgnitionServiceProvider',
        ],
        'aliases' => [
            'Flare' => 'Facade\\Ignition\\Facades\\Flare',
        ],
    ],
    'fideloper/proxy' => [
        'providers' => [
            0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
        ],
    ],
    'fruitcake/laravel-cors' => [
        'providers' => [
            0 => 'Fruitcake\\Cors\\CorsServiceProvider',
        ],
    ],
    'laracasts/flash' => [
        'providers' => [
            0 => 'Laracasts\\Flash\\FlashServiceProvider',
        ],
        'aliases' => [
            'Flash' => 'Laracasts\\Flash\\Flash',
        ],
    ],
    'laravel/tinker' => [
        'providers' => [
            0 => 'Laravel\\Tinker\\TinkerServiceProvider',
        ],
    ],
    'laravel/ui' => [
        'providers' => [
            0 => 'Laravel\\Ui\\UiServiceProvider',
        ],
    ],
    'livewire/livewire' => [
        'providers' => [
            0 => 'Livewire\\LivewireServiceProvider',
        ],
        'aliases' => [
            'Livewire' => 'Livewire\\Livewire',
        ],
    ],
    'nesbot/carbon' => [
        'providers' => [
            0 => 'Carbon\\Laravel\\ServiceProvider',
        ],
    ],
    'nunomaduro/collision' => [
        'providers' => [
            0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
        ],
    ],
    'spatie/laravel-permission' => [
        'providers' => [
            0 => 'Spatie\\Permission\\PermissionServiceProvider',
        ],
    ],
];
