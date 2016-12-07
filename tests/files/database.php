<?php

return [

    'default' => 'mysql',

    'connections' => [

        'mysql' => [
            'host'     => '127.0.0.1',
            'database' => 'db',
            'username' => 'user',
            'password' => 'password'
        ],

        'sqlite' => [
            'database' => 'db.sqlite'
        ]
    ]

];