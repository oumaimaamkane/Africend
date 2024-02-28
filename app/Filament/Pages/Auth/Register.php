<?php

 

namespace App\Filament\Pages\Auth;

 

use App\Models\CountrySeed;

use Illuminate\Support\Facades\Blade;

use Filament\Forms\Components\Wizard;

use Filament\Forms\Form;

use Filament\Forms;

use Filament\Pages\Auth\Register as baseRegister;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\HtmlString;

 

class Register extends baseRegister

{

    public function form(Form $form): Form

    {

        return $form

        ->statePath('data')
        ->schema([

            Forms\Components\TextInput::make('email')

            ->label(__('filament-panels::pages/auth/register.form.email.label'))

            ->email()

            ->unique()

            ->required(),  

            Forms\Components\TextInput::make('password')

            ->label(__('filament-panels::pages/auth/register.form.password.label'))

            ->password()

            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))

            ->required(),  

            Forms\Components\TextInput::make('password_confirmation')

            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))

            ->same('password')

            ->password()

            ->required(),  


            Forms\Components\TextInput::make('firstname')

            ->label(__('filament-panels::pages/auth/register.form.firstname.label'))

            ->required()

            ->maxLength(255),  

            Forms\Components\TextInput::make('lastname')

            ->label(__('filament-panels::pages/auth/register.form.lastname.label'))

            ->required()

            ->maxLength(255),                 

            Forms\Components\TextInput::make('phone')

            ->label(__('filament-panels::pages/auth/register.form.phone.label'))

            ->tel()

            ->required()

            ->maxLength(255),  

            Forms\Components\TextInput::make('city')

            ->label(__('filament-panels::pages/auth/register.form.city.label'))

            ->required()

            ->maxLength(255),  

            Forms\Components\TextInput::make('address')

            ->label(__('filament-panels::pages/auth/register.form.address.label'))

            ->maxLength(255),  

        ])
        ->columns(2);


            

    }



}