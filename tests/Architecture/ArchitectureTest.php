<?php

arch('ensure strict types are declared')
    ->expect('App')
    ->toUseStrictTypes();

arch('no debugging functions are used')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'ray'])
    ->not->toBeUsed();

arch('models')
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->toOnlyBeUsedIn([
        'App\Models',
        'App\Http\Controllers',
        'App\Http\Requests',
        'App\Services',
        'App\Repositories',
        'Database\Factories',
        'Database\Seeders',
    ]);

arch('no die or exit statements')
    ->expect(['die', 'exit'])
    ->not->toBeUsed();

arch('no sleep in production code')
    ->expect(['sleep', 'usleep'])
    ->not->toBeUsedIn('App');

arch('globals not used')
    ->expect('App')
    ->not->toUse([
        'extract',
        'compact',
        '$GLOBALS',
        '$_GET',
        '$_POST',
        '$_REQUEST',
        '$_SESSION',
        '$_COOKIE',
        '$_FILES',
    ]);

arch('facades are not used directly in services')
    ->expect('App\Services')
    ->not->toUse([
        'Illuminate\Support\Facades\DB',
        'Illuminate\Support\Facades\Cache',
        'Illuminate\Support\Facades\Storage',
    ])
    ->ignoring('App\Services\Interfaces');

arch('no inheritance from base controller in models')
    ->expect('App\Models')
    ->not->toExtend('App\Http\Controllers\Controller');

arch('repositories should be classes')
    ->expect('App\Repositories')
    ->toBeClasses()
    ->toHaveSuffix('Repository');

arch('ensure dependency injection over facades')
    ->expect('App\Services')
    ->not->toUse([
        'Illuminate\Support\Facades\Auth',
        'Illuminate\Support\Facades\Log',
        'Illuminate\Support\Facades\Mail',
    ]);

arch('no raw SQL queries')
    ->expect('App')
    ->not->toUse([
        'DB::raw',
        'DB::statement',
        'DB::unprepared',
    ]);