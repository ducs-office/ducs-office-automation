<?php return [
    'app' => [
        'name' => 'DUCS Office Automation',
        'organisation' => 'Department of Computer Science',
        'affiliation' => 'University of Delhi',
        'env' => 'testing',
        'debug' => true,
        'url' => 'https://ducs-office-automation.test',
        'asset_url' => null,
        'timezone' => 'Asia/Kolkata',
        'locale' => 'en',
        'fallback_locale' => 'en',
        'faker_locale' => 'en_IN',
        'key' => 'base64:A1FQAVfk2h5T3lzrjCtiocrH/Y2dpSM5BUqwaSG1kUE=',
        'cipher' => 'AES-256-CBC',
        'csrf_token_name' => 'csrf_token',
        'providers' => [
            0 => 'Illuminate\\Auth\\AuthServiceProvider',
            1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
            2 => 'Illuminate\\Bus\\BusServiceProvider',
            3 => 'Illuminate\\Cache\\CacheServiceProvider',
            4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
            5 => 'Illuminate\\Cookie\\CookieServiceProvider',
            6 => 'Illuminate\\Database\\DatabaseServiceProvider',
            7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
            8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
            9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
            10 => 'Illuminate\\Hashing\\HashServiceProvider',
            11 => 'Illuminate\\Mail\\MailServiceProvider',
            12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
            13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
            14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
            15 => 'Illuminate\\Queue\\QueueServiceProvider',
            16 => 'Illuminate\\Redis\\RedisServiceProvider',
            17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
            18 => 'Illuminate\\Session\\SessionServiceProvider',
            19 => 'Illuminate\\Translation\\TranslationServiceProvider',
            20 => 'Illuminate\\Validation\\ValidationServiceProvider',
            21 => 'Illuminate\\View\\ViewServiceProvider',
            22 => 'Laracasts\\Flash\\FlashServiceProvider',
            23 => 'Livewire\\LivewireServiceProvider',
            24 => 'App\\Providers\\AppServiceProvider',
            25 => 'App\\Providers\\AuthServiceProvider',
            26 => 'App\\Providers\\EventServiceProvider',
            27 => 'App\\Providers\\RouteServiceProvider',
        ],
        'aliases' => [
            'App' => 'Illuminate\\Support\\Facades\\App',
            'Arr' => 'Illuminate\\Support\\Arr',
            'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
            'Auth' => 'Illuminate\\Support\\Facades\\Auth',
            'Blade' => 'Illuminate\\Support\\Facades\\Blade',
            'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
            'Bus' => 'Illuminate\\Support\\Facades\\Bus',
            'Cache' => 'Illuminate\\Support\\Facades\\Cache',
            'Config' => 'Illuminate\\Support\\Facades\\Config',
            'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
            'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
            'DB' => 'Illuminate\\Support\\Facades\\DB',
            'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
            'Event' => 'Illuminate\\Support\\Facades\\Event',
            'File' => 'Illuminate\\Support\\Facades\\File',
            'Gate' => 'Illuminate\\Support\\Facades\\Gate',
            'Hash' => 'Illuminate\\Support\\Facades\\Hash',
            'Lang' => 'Illuminate\\Support\\Facades\\Lang',
            'Log' => 'Illuminate\\Support\\Facades\\Log',
            'Mail' => 'Illuminate\\Support\\Facades\\Mail',
            'Notification' => 'Illuminate\\Support\\Facades\\Notification',
            'Password' => 'Illuminate\\Support\\Facades\\Password',
            'Queue' => 'Illuminate\\Support\\Facades\\Queue',
            'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
            'Redis' => 'Illuminate\\Support\\Facades\\Redis',
            'Request' => 'Illuminate\\Support\\Facades\\Request',
            'Response' => 'Illuminate\\Support\\Facades\\Response',
            'Route' => 'Illuminate\\Support\\Facades\\Route',
            'Schema' => 'Illuminate\\Support\\Facades\\Schema',
            'Session' => 'Illuminate\\Support\\Facades\\Session',
            'Storage' => 'Illuminate\\Support\\Facades\\Storage',
            'Str' => 'Illuminate\\Support\\Str',
            'URL' => 'Illuminate\\Support\\Facades\\URL',
            'Validator' => 'Illuminate\\Support\\Facades\\Validator',
            'View' => 'Illuminate\\Support\\Facades\\View',
            'Flash' => 'Laracasts\\Flash\\FlashNotifier',
        ],
    ],
    'auth' => [
        'defaults' => [
            'guard' => 'web',
            'passwords' => 'users',
        ],
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
                'home' => '/staff',
            ],
            'scholars' => [
                'driver' => 'session',
                'provider' => 'scholars',
                'home' => '/scholars',
            ],
            'api' => [
                'driver' => 'token',
                'provider' => 'users',
                'hash' => false,
            ],
        ],
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => 'App\\Models\\User',
            ],
            'scholars' => [
                'driver' => 'eloquent',
                'model' => 'App\\Models\\Scholar',
            ],
        ],
        'passwords' => [
            'users' => [
                'provider' => 'users',
                'table' => 'password_resets',
                'expire' => 60,
            ],
            'scholars' => [
                'provider' => 'scholars',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ],
    ],
    'broadcasting' => [
        'default' => 'log',
        'connections' => [
            'pusher' => [
                'driver' => 'pusher',
                'key' => '',
                'secret' => '',
                'app_id' => '',
                'options' => [
                    'cluster' => 'mt1',
                    'useTLS' => true,
                ],
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
            ],
            'log' => [
                'driver' => 'log',
            ],
            'null' => [
                'driver' => 'null',
            ],
        ],
    ],
    'cache' => [
        'default' => 'array',
        'stores' => [
            'apc' => [
                'driver' => 'apc',
            ],
            'array' => [
                'driver' => 'array',
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'cache',
                'connection' => null,
            ],
            'file' => [
                'driver' => 'file',
                'path' => '/hdd/code/work/ducs-office-automation/storage/framework/cache/data',
            ],
            'memcached' => [
                'driver' => 'memcached',
                'persistent_id' => null,
                'sasl' => [
                    0 => null,
                    1 => null,
                ],
                'options' => [
                ],
                'servers' => [
                    0 => [
                        'host' => '127.0.0.1',
                        'port' => 11211,
                        'weight' => 100,
                    ],
                ],
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
            ],
            'dynamodb' => [
                'driver' => 'dynamodb',
                'key' => '',
                'secret' => '',
                'region' => 'us-east-1',
                'table' => 'cache',
                'endpoint' => null,
            ],
        ],
        'prefix' => 'ducs_office_automation_cache',
    ],
    'cors' => [
        'paths' => [
            0 => 'api/*',
        ],
        'allowed_methods' => [
            0 => '*',
        ],
        'allowed_origins' => [
            0 => '*',
        ],
        'allowed_origins_patterns' => [
        ],
        'allowed_headers' => [
            0 => '*',
        ],
        'exposed_headers' => [
        ],
        'max_age' => 0,
        'supports_credentials' => false,
    ],
    'database' => [
        'default' => 'sqlite',
        'connections' => [
            'sqlite' => [
                'driver' => 'sqlite',
                'url' => null,
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'mysql' => [
                'driver' => 'mysql',
                'url' => null,
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => ':memory:',
                'username' => 'root',
                'password' => 'secret',
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => [
                ],
            ],
            'pgsql' => [
                'driver' => 'pgsql',
                'url' => null,
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => ':memory:',
                'username' => 'root',
                'password' => 'secret',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ],
            'sqlsrv' => [
                'driver' => 'sqlsrv',
                'url' => null,
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => ':memory:',
                'username' => 'root',
                'password' => 'secret',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
            ],
        ],
        'migrations' => 'migrations',
        'redis' => [
            'client' => 'phpredis',
            'options' => [
                'cluster' => 'redis',
                'prefix' => 'ducs_office_automation_database_',
            ],
            'default' => [
                'url' => null,
                'host' => '127.0.0.1',
                'password' => null,
                'port' => '6379',
                'database' => 0,
            ],
            'cache' => [
                'url' => null,
                'host' => '127.0.0.1',
                'password' => null,
                'port' => '6379',
                'database' => 1,
            ],
        ],
        'operators' => [
            'greater_than' => '>',
            'less_than' => '<',
            'equals' => '=',
            'like' => 'like',
            'not_equals' => '<>',
        ],
    ],
    'filesystems' => [
        'default' => 'local',
        'cloud' => 's3',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => '/hdd/code/work/ducs-office-automation/storage/app',
            ],
            'public' => [
                'driver' => 'local',
                'root' => '/hdd/code/work/ducs-office-automation/storage/app/public',
                'url' => 'https://ducs-office-automation.test/storage',
                'visibility' => 'public',
            ],
            's3' => [
                'driver' => 's3',
                'key' => '',
                'secret' => '',
                'region' => 'us-east-1',
                'bucket' => '',
                'url' => null,
            ],
        ],
    ],
    'hashing' => [
        'driver' => 'bcrypt',
        'bcrypt' => [
            'rounds' => '4',
        ],
        'argon' => [
            'memory' => 1024,
            'threads' => 2,
            'time' => 2,
        ],
    ],
    'insights' => [
        'preset' => 'laravel',
        'ide' => null,
        'exclude' => [
        ],
        'add' => [
            'NunoMaduro\\PhpInsights\\Domain\\Metrics\\Architecture\\Classes' => [
                0 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenFinalClasses',
            ],
        ],
        'remove' => [
            0 => 'SlevomatCodingStandard\\Sniffs\\Namespaces\\AlphabeticallySortedUsesSniff',
            1 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\DeclareStrictTypesSniff',
            2 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\DisallowMixedTypeHintSniff',
            3 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenDefineFunctions',
            4 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenNormalClasses',
            5 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenTraits',
            6 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\TypeHintDeclarationSniff',
        ],
        'config' => [
            'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenPrivateMethods' => [
                'title' => 'The usage of private methods is not idiomatic in Laravel.',
            ],
        ],
    ],
    'logging' => [
        'default' => 'stack',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => [
                    0 => 'daily',
                ],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => '/hdd/code/work/ducs-office-automation/storage/logs/laravel.log',
                'level' => 'debug',
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => '/hdd/code/work/ducs-office-automation/storage/logs/laravel.log',
                'level' => 'debug',
                'days' => 14,
            ],
            'slack' => [
                'driver' => 'slack',
                'url' => null,
                'username' => 'Laravel Log',
                'emoji' => ':boom:',
                'level' => 'critical',
            ],
            'papertrail' => [
                'driver' => 'monolog',
                'level' => 'debug',
                'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
                'handler_with' => [
                    'host' => null,
                    'port' => null,
                ],
            ],
            'stderr' => [
                'driver' => 'monolog',
                'handler' => 'Monolog\\Handler\\StreamHandler',
                'formatter' => null,
                'with' => [
                    'stream' => 'php://stderr',
                ],
            ],
            'syslog' => [
                'driver' => 'syslog',
                'level' => 'debug',
            ],
            'errorlog' => [
                'driver' => 'errorlog',
                'level' => 'debug',
            ],
        ],
    ],
    'mail' => [
        'default' => 'array',
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => 'smtp.mailtrap.io',
                'port' => '2525',
                'encryption' => null,
                'username' => '3da2d379682502',
                'password' => 'f32715a701909d',
                'timeout' => null,
            ],
            'ses' => [
                'transport' => 'ses',
            ],
            'mailgun' => [
                'transport' => 'mailgun',
            ],
            'postmark' => [
                'transport' => 'postmark',
            ],
            'sendmail' => [
                'transport' => 'sendmail',
                'path' => '/usr/sbin/sendmail -bs',
            ],
            'log' => [
                'transport' => 'log',
                'channel' => null,
            ],
            'array' => [
                'transport' => 'array',
            ],
        ],
        'from' => [
            'address' => 'hello@example.com',
            'name' => 'Example',
        ],
        'markdown' => [
            'theme' => 'default',
            'paths' => [
                0 => '/hdd/code/work/ducs-office-automation/resources/views/vendor/mail',
            ],
        ],
    ],
    'permission' => [
        'models' => [
            'permission' => 'Spatie\\Permission\\Models\\Permission',
            'role' => 'Spatie\\Permission\\Models\\Role',
        ],
        'table_names' => [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ],
        'column_names' => [
            'model_morph_key' => 'model_id',
        ],
        'display_permission_in_exception' => false,
        'enable_wildcard_permission' => false,
        'cache' => [
            'expiration_time' => DateInterval::__set_state([
                'y' => 0,
                'm' => 0,
                'd' => 0,
                'h' => 24,
                'i' => 0,
                's' => 0,
                'f' => 0.0,
                'weekday' => 0,
                'weekday_behavior' => 0,
                'first_last_day_of' => 0,
                'invert' => 0,
                'days' => false,
                'special_type' => 0,
                'special_amount' => 0,
                'have_weekday_relative' => 0,
                'have_special_relative' => 0,
            ]),
            'key' => 'spatie.permission.cache',
            'model_key' => 'name',
            'store' => 'default',
        ],
        'static' => [
            'permissions' => [
                'outgoing letters' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'incoming letters' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'remarks' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'letter reminders' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'colleges' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'programmes' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'courses' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'roles' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'users' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'teaching records' => [
                    0 => 'view',
                    1 => 'start',
                    2 => 'extend',
                ],
                'scholars' => [
                    0 => 'view',
                    1 => 'create',
                    2 => 'edit',
                    3 => 'delete',
                ],
                'leaves' => [
                    0 => 'respond',
                ],
                'phd course work' => [
                    0 => 'mark completed',
                ],
                'scholar progress reports' => [
                    0 => 'add',
                    1 => 'view',
                    2 => 'delete',
                ],
                'scholar documents' => [
                    0 => 'add',
                    1 => 'view',
                    2 => 'delete',
                ],
                'phd seminar' => [
                    0 => 'add schedule',
                    1 => 'finalize',
                ],
                'title approval' => [
                    0 => 'approve',
                ],
                'scholar examiner' => [
                    0 => 'recommend',
                    1 => 'approve',
                ],
            ],
            'roles' => [
                'admin' => [
                    'outgoing letters' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'incoming letters' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'remarks' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'letter reminders' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'colleges' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'programmes' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'courses' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'roles' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'users' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'teaching records' => [
                        0 => 'view',
                        1 => 'start',
                        2 => 'extend',
                    ],
                    'scholars' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'scholar documents' => [
                        0 => 'add',
                        1 => 'view',
                        2 => 'delete',
                    ],
                    'phd seminar' => [
                        0 => 'add schedule',
                        1 => 'finalize',
                    ],
                    'title approval' => [
                        0 => 'approve',
                    ],
                    'scholar examiner' => [
                        0 => 'recommend',
                        1 => 'approve',
                    ],
                ],
                'DRC Member' => [
                    'scholars' => [
                        0 => 'view',
                        1 => 'create',
                        2 => 'edit',
                        3 => 'delete',
                    ],
                    'leaves' => [
                        0 => 'respond',
                    ],
                    'phd course work' => [
                        0 => 'mark completed',
                    ],
                    'scholar progress reports' => [
                        0 => 'add',
                        1 => 'view',
                    ],
                    'scholar documents' => [
                        0 => 'add',
                        1 => 'view',
                        2 => 'delete',
                    ],
                    'phd seminar' => [
                        0 => 'add schedule',
                        1 => 'finalize',
                    ],
                    'title approval' => [
                        0 => 'approve',
                    ],
                ],
            ],
        ],
    ],
    'queue' => [
        'default' => 'sync',
        'connections' => [
            'sync' => [
                'driver' => 'sync',
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'retry_after' => 90,
            ],
            'beanstalkd' => [
                'driver' => 'beanstalkd',
                'host' => 'localhost',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => 0,
            ],
            'sqs' => [
                'driver' => 'sqs',
                'key' => '',
                'secret' => '',
                'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
                'queue' => 'your-queue-name',
                'region' => 'us-east-1',
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => null,
            ],
        ],
        'failed' => [
            'driver' => 'database',
            'database' => 'sqlite',
            'table' => 'failed_jobs',
        ],
    ],
    'services' => [
        'mailgun' => [
            'domain' => null,
            'secret' => null,
            'endpoint' => 'api.mailgun.net',
        ],
        'postmark' => [
            'token' => null,
        ],
        'ses' => [
            'key' => '',
            'secret' => '',
            'region' => 'us-east-1',
        ],
    ],
    'session' => [
        'driver' => 'array',
        'lifetime' => '120',
        'expire_on_close' => false,
        'encrypt' => false,
        'files' => '/hdd/code/work/ducs-office-automation/storage/framework/sessions',
        'connection' => null,
        'table' => 'sessions',
        'store' => null,
        'lottery' => [
            0 => 2,
            1 => 100,
        ],
        'cookie' => 'ducs_office_automation_session',
        'path' => '/',
        'domain' => null,
        'secure' => null,
        'http_only' => true,
        'same_site' => null,
    ],
    'view' => [
        'paths' => [
            0 => '/hdd/code/work/ducs-office-automation/resources/views',
        ],
        'compiled' => '/hdd/code/work/ducs-office-automation/storage/framework/views',
    ],
    'erd-generator' => [
        'directories' => [
            0 => '/hdd/code/work/ducs-office-automation/app',
        ],
        'ignore' => [
        ],
        'recursive' => true,
        'use_db_schema' => true,
        'use_column_types' => true,
        'table' => [
            'header_background_color' => '#d3d3d3',
            'header_font_color' => '#333333',
            'row_background_color' => '#ffffff',
            'row_font_color' => '#333333',
        ],
        'graph' => [
            'style' => 'filled',
            'bgcolor' => '#F7F7F7',
            'fontsize' => 12,
            'labelloc' => 't',
            'concentrate' => true,
            'splines' => 'polyline',
            'overlap' => false,
            'nodesep' => 1,
            'rankdir' => 'LR',
            'pad' => 0.5,
            'ranksep' => 2,
            'esep' => true,
            'fontname' => 'Helvetica Neue',
        ],
        'node' => [
            'margin' => 0,
            'shape' => 'rectangle',
            'fontname' => 'Helvetica Neue',
        ],
        'edge' => [
            'color' => '#003049',
            'penwidth' => 1.8,
            'fontname' => 'Helvetica Neue',
        ],
        'relations' => [
            'HasOne' => [
                'dir' => 'both',
                'color' => '#D62828',
                'arrowhead' => 'tee',
                'arrowtail' => 'none',
            ],
            'BelongsTo' => [
                'dir' => 'both',
                'color' => '#F77F00',
                'arrowhead' => 'tee',
                'arrowtail' => 'crow',
            ],
            'HasMany' => [
                'dir' => 'both',
                'color' => '#FCBF49',
                'arrowhead' => 'crow',
                'arrowtail' => 'none',
            ],
        ],
    ],
    'flare' => [
        'key' => null,
        'reporting' => [
            'anonymize_ips' => true,
            'collect_git_information' => false,
            'report_queries' => true,
            'maximum_number_of_collected_queries' => 200,
            'report_query_bindings' => true,
            'report_view_data' => true,
            'grouping_type' => null,
        ],
        'send_logs_as_events' => true,
    ],
    'ignition' => [
        'editor' => 'phpstorm',
        'theme' => 'light',
        'enable_share_button' => true,
        'register_commands' => false,
        'ignored_solution_providers' => [
            0 => 'Facade\\Ignition\\SolutionProviders\\MissingPackageSolutionProvider',
        ],
        'enable_runnable_solutions' => null,
        'remote_sites_path' => '',
        'local_sites_path' => '',
        'housekeeping_endpoint_prefix' => '_ignition',
    ],
    'trustedproxy' => [
        'proxies' => null,
        'headers' => 30,
    ],
    'tinker' => [
        'commands' => [
        ],
        'alias' => [
        ],
        'dont_alias' => [
            0 => 'App\\Nova',
        ],
    ],
];
