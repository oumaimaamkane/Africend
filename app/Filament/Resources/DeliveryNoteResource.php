<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryNoteResource\Pages;
use App\Filament\Resources\DeliveryNoteResource\RelationManagers;
use App\Models\DeliveryNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Facades\Filament;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryNoteResource extends Resource
{
    protected static ?string $model = DeliveryNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->label(__('filament::resources/warehouses.deliery_notes.form.reference'))
            ->searchable(),
            Tables\Columns\TextColumn::make('user')
            ->label(__('filament::resources/warehouses.deliery_notes.form.seller'))                
            ->formatStateUsing(fn($state) => $state->firstname . ' ' . $state->lastname)
            ->hidden(function(){
                if(auth()->user()->hasRole('Seller')){
                    return true;
                }
            }),
            Tables\Columns\TextColumn::make('nbr_orders')
            ->label(__('filament::resources/warehouses.deliery_notes.form.nbr_orders')),
            // Tables\Columns\TextColumn::make('pickup_city'),
            // Tables\Columns\TextColumn::make('pickup_address'),
        ])
        ->modifyQueryUsing(function(Builder $query){
            if(auth()->user()->hasRole('Seller')){
                return $query->where('user_id' , '=' , auth()->id())->where('type' , '=' , 'BL');
            }
        })
        ->filters([
            Tables\Filters\SelectFilter::make('creaed_at')
                    ->label('Date')
                    ->form([
                            Forms\Components\DatePicker::make('created_from'),
                            Forms\Components\DatePicker::make('created_until')->default(now()),
                        ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('A4(x4)')
                ->url(fn (DeliveryNote $record): string => route('filament.resources.orders-resource.pages.print-labels' , ['number' => 4 , 'record' => $record])),

            ])
            ->label(__('filament::resources/warehouses.actions.print_labels'))
            ->icon('heroicon-s-printer')
            ->color('gray')
            ->link(),
        ])
        ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->emptyStateActions([
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
            'index' => Pages\ListDeliveryNotes::route('/'),
            'create' => Pages\CreateDeliveryNote::route('/create'),
            'edit' => Pages\EditDeliveryNote::route('/{record}/edit'),
        ];
    }
}
