<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament::resources/other.countries.heading.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::resources/other.countries.heading.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('code')->required(),
                Forms\Components\TextInput::make('currency')->required(),
                Forms\Components\TextInput::make('lead_confirmed_fees')->numeric(),
                Forms\Components\TextInput::make('lead_delivered_fees')->numeric(),
                Forms\Components\TextInput::make('lead_canceled_fees')->numeric(),
                Forms\Components\TextInput::make('lead_returned_fees')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('lead_confirmed_fees'),
                Tables\Columns\TextColumn::make('lead_delivered_fees'),
                Tables\Columns\TextColumn::make('lead_canceled_fees'),
                Tables\Columns\TextColumn::make('lead_returned_fees'),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
