<?php
use Cake\Core\Configure;

return [
    'HybridAuth' => [

        'providers' => [

            'Facebook' => [
                'enabled' => true,
                'scope'   => 'email, user_about_me, user_birthday, publish_actions, user_hometown', // optional
                'keys' => [
                    'id'     => env('FACEBOOK_APP_ID'),
                    'secret' => env('FACEBOOK_APP_SECRET')
                ],
            ],

            'Google'  => [
                'enabled' => true,
                'scope'   => (
                    // https://developers.google.com/identity/protocols/googlescopes
                    'https://www.googleapis.com/auth/userinfo.profile' . ' ' .
                    'https://www.googleapis.com/auth/userinfo.email'
                ),
                'keys' => [
                    'id'     => env('GOOGLE_AUTH_CREDENTIALS_CLIENT'),
                    'secret' => env('GOOGLE_AUTH_CREDENTIALS_SECRET')
                ]
            ],

            'Twitter' => [
                'enabled' => true,
                'keys' => [
                    'key'    => env('TWITTER_AUTH_CREDENTIALS_KEY'),
                    'secret' => env('TWITTER_AUTH_CREDENTIALS_SECRET')
                ],
                'includeEmail' => true // Only if your app is whitelisted by Twitter Support
            ]
        ],

        'debug_mode' => Configure::read('debug'),
        'debug_file' => LOGS . 'hybridauth.log',
    ]
];
