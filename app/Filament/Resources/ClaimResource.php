<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClaimResource\Pages;
use App\Filament\Resources\ClaimResource\RelationManagers;
use App\Models\Claim;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?int $navigationSort = 6;


    public static function getModelLabel(): string
    {
        return __('filament::resources/other.claims.heading.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::resources/other.claims.heading.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                ->label(__('filament::resources/other.claims.form.type'))
                ->options([
                    'Changement d adresse' => __('filament::resources/other.claims.form.Change of address'),
                    'Annulation de commande' => __('filament::resources/other.claims.form.Order cancellation'),
                    'Remboursement' => __('filament::resources/other.claims.form.Refund'),
                    'Changement de prix' =>__('filament::resources/other.claims.form.Price change'),
                    'Facturation' => __('filament::resources/other.claims.form.Billing'),
                    'Autres' => __('filament::resources/other.claims.form.Others')
                   
                ]),
                Forms\Components\Select::make('country_id')
                ->label(__('filament::resources/other.claims.form.country'))
                ->options(Country::all()->pluck('name' , 'id')),
                Forms\Components\TextInput::make('city')
                ->label(__('filament::resources/other.claims.form.city')),
                Forms\Components\MarkdownEditor::make('message')
                ->label(__('filament::resources/other.claims.form.message'))
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user')->label(__('filament::resources/users.heading.singular'))
                ->formatStateUsing(fn($state) => $state->firstname . ' ' . $state->lastname),
                Tables\Columns\TextColumn::make('country.name')
                ->label(__('filament::resources/other.claims.form.country')),
                Tables\Columns\TextColumn::make('city')
                ->label(__('filament::resources/other.claims.form.city')),
                Tables\Columns\TextColumn::make('type')
                ->label(__('filament::resources/other.claims.form.type'))
                ->badge()
                ->colors([
                    'primary'=>'Change of address',
                    'danger' => 'Order cancellation',
                    'amber' => 'Refund',
                    'success' => 'Billing',
                    'dark' => 'Others',
                ]),
                Tables\Columns\TextColumn::make('message')
                ->label(__('filament::resources/other.claims.form.message'))
                ->limit(12),
                Tables\Columns\TextColumn::make('status')
                ->label(__('filament::resources/orders.form.status'))
                ->sortable()
                ->badge()
                ->colors([
                    'info' => 'Not yet processed',
                    'orange'=>'Processing',
                    'green' =>'Processed'
                ])
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
            'index' => Pages\ListClaims::route('/'),
            'create' => Pages\CreateClaim::route('/create'),
            'edit' => Pages\EditClaim::route('/{record}/edit'),
        ];
    }
}
