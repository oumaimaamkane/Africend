<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invc::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 7;

    public static function getModelLabel(): string
    {
        return __('filament::resources/other.invoices.heading.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::resources/other.invoices.heading.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                ->searchable(),
                Tables\Columns\TextColumn::make('user')
                ->label(__('filament::resources/users.heading.singular'))
                    ->formatStateUsing(fn($state) => $state->firstname . ' ' . $state->lastname)
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                ->label(__('filament::resources/users.table.country')),

                Tables\Columns\TextColumn::make('nbr_orders')
                ->label(__('filament::resources/other.invoices.form.amount'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                ->label(__('filament::resources/other.invoices.form.amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_net')
                ->label(__('filament::resources/other.invoices.form.amount_net'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->label(__('filament::resources/other.invoices.form.status'))
                ->badge()
                ->colors([
                    'danger' => 'Non Payé',
                    'success' => 'Payé',
                ]),
             
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateActions([
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
