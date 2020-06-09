<?php return array (
  'app' => 
  array (
    'name' => 'DUCS Office Automation',
    'organisation' => 'Department of Computer Science',
    'affiliation' => 'University of Delhi',
    'env' => 'testing',
    'debug' => true,
    'url' => 'https://ducs-office-automation.test',
    'asset_url' => NULL,
    'timezone' => 'Asia/Kolkata',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_IN',
    'key' => 'base64:A1FQAVfk2h5T3lzrjCtiocrH/Y2dpSM5BUqwaSG1kUE=',
    'cipher' => 'AES-256-CBC',
    'csrf_token_name' => 'csrf_token',
    'providers' => 
    array (
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
    ),
    'aliases' => 
    array (
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
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
        'home' => '/staff',
      ),
      'scholars' => 
      array (
        'driver' => 'session',
        'provider' => 'scholars',
        'home' => '/scholars',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
        'hash' => false,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
      'scholars' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\Scholar',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
      'scholars' => 
      array (
        'provider' => 'scholars',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'useTLS' => true,
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'array',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/hdd/code/work/ducs-office-automation/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
    ),
    'prefix' => 'ducs_office_automation_cache',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'sqlite',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
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
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
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
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => ':memory:',
        'username' => 'root',
        'password' => 'secret',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'ducs_office_automation_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 1,
      ),
    ),
    'operators' => 
    array (
      'greater_than' => '>',
      'less_than' => '<',
      'equals' => '=',
      'like' => 'like',
      'not_equals' => '<>',
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/hdd/code/work/ducs-office-automation/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/hdd/code/work/ducs-office-automation/storage/app/public',
        'url' => 'https://ducs-office-automation.test/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
      ),
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => '4',
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
  ),
  'insights' => 
  array (
    'preset' => 'laravel',
    'ide' => NULL,
    'exclude' => 
    array (
    ),
    'add' => 
    array (
      'NunoMaduro\\PhpInsights\\Domain\\Metrics\\Architecture\\Classes' => 
      array (
        0 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenFinalClasses',
      ),
    ),
    'remove' => 
    array (
      0 => 'SlevomatCodingStandard\\Sniffs\\Namespaces\\AlphabeticallySortedUsesSniff',
      1 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\DeclareStrictTypesSniff',
      2 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\DisallowMixedTypeHintSniff',
      3 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenDefineFunctions',
      4 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenNormalClasses',
      5 => 'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenTraits',
      6 => 'SlevomatCodingStandard\\Sniffs\\TypeHints\\TypeHintDeclarationSniff',
    ),
    'config' => 
    array (
      'NunoMaduro\\PhpInsights\\Domain\\Insights\\ForbiddenPrivateMethods' => 
      array (
        'title' => 'The usage of private methods is not idiomatic in Laravel.',
      ),
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'daily',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/hdd/code/work/ducs-office-automation/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/hdd/code/work/ducs-office-automation/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'array',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => 'smtp.mailtrap.io',
        'port' => '2525',
        'encryption' => NULL,
        'username' => '3da2d379682502',
        'password' => 'f32715a701909d',
        'timeout' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Example',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/hdd/code/work/ducs-office-automation/resources/views/vendor/mail',
      ),
    ),
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'model_morph_key' => 'model_id',
    ),
    'display_permission_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      DateInterval::__set_state(array(
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
      )),
      'key' => 'spatie.permission.cache',
      'model_key' => 'name',
      'store' => 'default',
    ),
    'static' => 
    array (
      'permissions' => 
      array (
        'outgoing letters' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'incoming letters' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'remarks' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'letter reminders' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'colleges' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'programmes' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'courses' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'roles' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'users' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'teaching records' => 
        array (
          0 => 'view',
          1 => 'start',
          2 => 'extend',
        ),
        'scholars' => 
        array (
          0 => 'view',
          1 => 'create',
          2 => 'edit',
          3 => 'delete',
        ),
        'leaves' => 
        array (
          0 => 'respond',
        ),
        'phd course work' => 
        array (
          0 => 'mark completed',
        ),
        'scholar progress reports' => 
        array (
          0 => 'add',
          1 => 'view',
          2 => 'delete',
        ),
        'scholar documents' => 
        array (
          0 => 'add',
          1 => 'view',
          2 => 'delete',
        ),
        'phd seminar' => 
        array (
          0 => 'add schedule',
          1 => 'finalize',
        ),
        'title approval' => 
        array (
          0 => 'approve',
        ),
        'scholar examiner' => 
        array (
          0 => 'recommend',
          1 => 'approve',
        ),
      ),
      'roles' => 
      array (
        'admin' => 
        array (
          'outgoing letters' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'incoming letters' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'remarks' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'letter reminders' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'colleges' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'programmes' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'courses' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'roles' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'users' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'teaching records' => 
          array (
            0 => 'view',
            1 => 'start',
            2 => 'extend',
          ),
          'scholars' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'scholar documents' => 
          array (
            0 => 'add',
            1 => 'view',
            2 => 'delete',
          ),
          'phd seminar' => 
          array (
            0 => 'add schedule',
            1 => 'finalize',
          ),
          'title approval' => 
          array (
            0 => 'approve',
          ),
          'scholar examiner' => 
          array (
            0 => 'recommend',
            1 => 'approve',
          ),
        ),
        'DRC Member' => 
        array (
          'scholars' => 
          array (
            0 => 'view',
            1 => 'create',
            2 => 'edit',
            3 => 'delete',
          ),
          'leaves' => 
          array (
            0 => 'respond',
          ),
          'phd course work' => 
          array (
            0 => 'mark completed',
          ),
          'scholar progress reports' => 
          array (
            0 => 'add',
            1 => 'view',
          ),
          'scholar documents' => 
          array (
            0 => 'add',
            1 => 'view',
            2 => 'delete',
          ),
          'phd seminar' => 
          array (
            0 => 'add schedule',
            1 => 'finalize',
          ),
          'title approval' => 
          array (
            0 => 'approve',
          ),
        ),
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
      ),
    ),
    'failed' => 
    array (
      'driver' => 'database',
      'database' => 'sqlite',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
  ),
  'session' => 
  array (
    'driver' => 'array',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/hdd/code/work/ducs-office-automation/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'ducs_office_automation_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/hdd/code/work/ducs-office-automation/resources/views',
    ),
    'compiled' => '/hdd/code/work/ducs-office-automation/storage/framework/views',
  ),
  'erd-generator' => 
  array (
    'directories' => 
    array (
      0 => '/hdd/code/work/ducs-office-automation/app',
    ),
    'ignore' => 
    array (
    ),
    'recursive' => true,
    'use_db_schema' => true,
    'use_column_types' => true,
    'table' => 
    array (
      'header_background_color' => '#d3d3d3',
      'header_font_color' => '#333333',
      'row_background_color' => '#ffffff',
      'row_font_color' => '#333333',
    ),
    'graph' => 
    array (
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
    ),
    'node' => 
    array (
      'margin' => 0,
      'shape' => 'rectangle',
      'fontname' => 'Helvetica Neue',
    ),
    'edge' => 
    array (
      'color' => '#003049',
      'penwidth' => 1.8,
      'fontname' => 'Helvetica Neue',
    ),
    'relations' => 
    array (
      'HasOne' => 
      array (
        'dir' => 'both',
        'color' => '#D62828',
        'arrowhead' => 'tee',
        'arrowtail' => 'none',
      ),
      'BelongsTo' => 
      array (
        'dir' => 'both',
        'color' => '#F77F00',
        'arrowhead' => 'tee',
        'arrowtail' => 'crow',
      ),
      'HasMany' => 
      array (
        'dir' => 'both',
        'color' => '#FCBF49',
        'arrowhead' => 'crow',
        'arrowtail' => 'none',
      ),
    ),
  ),
  'flare' => 
  array (
    'key' => NULL,
    'reporting' => 
    array (
      'anonymize_ips' => true,
      'collect_git_information' => false,
      'report_queries' => true,
      'maximum_number_of_collected_queries' => 200,
      'report_query_bindings' => true,
      'report_view_data' => true,
      'grouping_type' => NULL,
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'light',
    'enable_share_button' => true,
    'register_commands' => false,
    'ignored_solution_providers' => 
    array (
      0 => 'Facade\\Ignition\\SolutionProviders\\MissingPackageSolutionProvider',
    ),
    'enable_runnable_solutions' => NULL,
    'remote_sites_path' => '',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
  ),
  'trustedproxy' => 
  array (
    'proxies' => NULL,
    'headers' => 30,
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
