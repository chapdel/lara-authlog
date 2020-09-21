<?php

namespace Chapdel\AuthLog;

trait EventMap
{
    /**
     * The Authentication Log event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        'Illuminate\Auth\Events\Login' => [
            'Chapdel\AuthLog\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'Chapdel\AuthLog\Listeners\LogSuccessfulLogout',
        ],

        'Illuminate\Auth\Events\Registered' => [
            'Chapdel\AuthLog\Listeners\LogSuccessfulRegister',
        ],
    ];
}
