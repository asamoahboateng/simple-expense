<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NativePHP Application Version
    |--------------------------------------------------------------------------
    */
    'version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | NativePHP App ID
    |--------------------------------------------------------------------------
    | A unique, reverse-domain identifier for your app used by the OS.
    */
    'app_id' => env('NATIVEPHP_APP_ID', 'com.kalourmade.simple-expense'),

    /*
    |--------------------------------------------------------------------------
    | Updater
    |--------------------------------------------------------------------------
    */
    'updater' => [
        /**
         * Whether or not the updater is enabled.
         */
        'enabled' => env('NATIVEPHP_UPDATER_ENABLED', false),

        /**
         * The updater provider to use.
         * Supported: "github", "s3", "spaces"
         */
        'default' => env('NATIVEPHP_UPDATER_PROVIDER', 'github'),

        'providers' => [
            'github' => [
                'driver' => 'github',
                'repo' => env('GITHUB_REPO'),
                'owner' => env('GITHUB_OWNER'),
                'token' => env('GITHUB_TOKEN'),
                'vPrefixedTagName' => env('GITHUB_V_PREFIXED_TAG_NAME', true),
                'private' => env('GITHUB_PRIVATE', false),
                'channel' => env('GITHUB_CHANNEL', 'latest'),
                'releaseType' => env('GITHUB_RELEASE_TYPE', 'draft'),
            ],

            's3' => [
                'driver' => 's3',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'endpoint' => env('AWS_ENDPOINT'),
                'path' => env('NATIVEPHP_UPDATER_PATH', null),
            ],

            'spaces' => [
                'driver' => 'spaces',
                'key' => env('DO_SPACES_KEY_ID'),
                'secret' => env('DO_SPACES_SECRET_ACCESS_KEY'),
                'name' => env('DO_SPACES_NAME'),
                'region' => env('DO_SPACES_REGION'),
                'path' => env('NATIVEPHP_UPDATER_PATH', null),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup On Update
    |--------------------------------------------------------------------------
    */
    'cleanup_on_update' => false,

    /*
    |--------------------------------------------------------------------------
    | Deep Links
    |--------------------------------------------------------------------------
    */
    'deeplinks' => [],

    /*
    |--------------------------------------------------------------------------
    | Build Hooks
    |--------------------------------------------------------------------------
    | Commands to run before and after the native build process.
    | These run from the project root directory.
    */
    'prebuild' => [
        'npm run build',
        'php artisan optimize',
    ],

    'postbuild' => [
        'php artisan optimize:clear',
    ],

];
