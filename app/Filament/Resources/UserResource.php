<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('filament::resources/users.heading.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::resources/users.heading.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('details')
                ->heading(__('filament-panels::pages/auth/register.form.step2.label'))
                ->schema([
                        Forms\Components\TextInput::make('firstname')
                        ->label(__('filament::resources/users.table.firstname'))
                       ->required()
                       ->maxLength(255),  

                       Forms\Components\TextInput::make('lastname')
                       ->label(__('filament::resources/users.table.lastname'))
                       ->required()
                       ->maxLength(255),  
                      
                       Forms\Components\TextInput::make('phone')
                       ->label(__('filament::resources/users.table.phone'))
                       ->tel()
                       ->required()
                       ->maxLength(255),  
                      
                       Forms\Components\TextInput::make('city')
                       ->label(__('filament::resources/users.table.city'))
                       ->required()
                       ->maxLength(255),  
                       Forms\Components\TextInput::make('address')
                       ->label(__('filament-panels::pages/auth/register.form.address.label'))
                       ->maxLength(255),  

                       Forms\Components\TextInput::make('email')
                       ->label(__('filament::resources/users.table.email'))
                       ->email()
                       ->unique(ignoreRecord:true)
                       ->required()
                       ->disabledOn('edit')
                       ->readOnlyOn('edit'),  

                       Forms\Components\TextInput::make('password')
                       ->label(__('filament-panels::pages/auth/register.form.password.label'))
                       ->password()
                       ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                       ->required()
                       ->hiddenOn('edit'),  
                       
                       Forms\Components\Select::make('role_id')
                       ->label(__('filament-shield::filament-shield.resource.label.role'))
                       ->relationship('role' , 'name')
                       ->required(),
                ])
                ->icon('heroicon-o-user')
                ->columns(2)
                ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')
                ->label(__('filament::resources/users.table.firstname'))
                ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                ->label(__('filament::resources/users.table.lastname'))
                ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->label(__('filament::resources/users.table.email'))
                ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                ->label(__('filament::resources/users.table.phone'))
                ->searchable(),
                Tables\Columns\TextColumn::make('role')
                ->label(__('filament-shield::filament-shield.resource.label.role'))
                ->state(function(User $user){
                    return $user->getRoleNames();
                })
                ,

                Tables\Columns\TextColumn::make('city')
                ->label(__('filament::resources/users.table.city')),

              

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
