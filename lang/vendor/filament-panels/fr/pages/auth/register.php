<?php

return [

    'title' => 'Inscription',

    'heading' => 'S\'inscrire',

    'actions' => [

        'login' => [
            'before' => 'ou',
            'label' => 'connectez-vous à votre compte',
        ],

    ],

    'form' => [
        'step1' => [
            'label' => 'Informations sur le compte',
        ],
        'step2' => [
            'label' => 'Informations personnelles',
        ],
        'email' => [
            'label' => 'Email',
        ],
        'firstname' => [
            'label' => 'Prénom',
        ],
        'lastname' => [
            'label' => 'Nom de famille',
        ],
        'phone' => [
            'label' => 'Numéro de téléphone',
        ],
        'country' => [
            'label' => 'Pays',
        ],
        'city' => [
            'label' => 'Ville',
        ],
        'address' => [
            'label' => 'Adresse',
        ],
        'cin' => [
            'label' => 'Numéro d\'identification national',
        ],
        'company' => [
            'label' => 'Société',
        ],
        'password' => [
            'label' => 'Mot de passe',
            'validation_attribute' => 'Mot de passe',
        ],
        'password_confirmation' => [
            'label' => 'Confirmer le mot de passe',
        ],
        'actions' => [
            'register' => [
                'label' => 'S\'inscrire',
            ],
        ],
    ],
    

    'notifications' => [

        'throttled' => [
            'title' => 'Trop de tentatives d\'inscription',
            'body' => 'Veuillez réessayer dans :seconds secondes.',
        ],

    ],

];
