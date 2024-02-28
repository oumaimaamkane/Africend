<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Option;
use App\Models\User;
use App\Models\DeliveryNote;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Str;
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('filament::resources/orders.heading.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament::resources/orders.heading.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([ Forms\Components\Group::make()
        ->schema([
           Forms\Components\Section::make()
           ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('filament::resources/orders.form.name'))
                   ->required(),
                Forms\Components\TextInput::make('number')
                    ->tel()
                   ->required(),
                Forms\Components\Select::make('country_id')
                ->label(__('filament::resources/products.form.country'))
                    ->relationship('country', 'name')
                    ->required(),
                Forms\Components\TextInput::make('city')
                ->label(__('filament::resources/users.table.city'))
                ->required()
                ->maxLength(255),  
                Forms\Components\TextInput::make('address')
                ->label(__('filament::resources/orders.form.address'))
                ->maxLength(255),  
           ])
           ->columns(2),

             Forms\Components\Section::make()
            ->schema([
            Forms\Components\Select::make('product')
            ->label(__('filament::resources/products.form.title'))
            ->options(function(){
                if(auth()->user()->hasRole('Seller')){
                  return Product::where('user_id', auth()->id())
                  ->where(function ($query) {
                        $query->where('status', '=' ,'In warehouse');
                    })
                  ->get()->pluck('title' , 'id');
                }else{
                    return Product::all()->pluck('title' , 'id');
                }
            })
            ->live(onBlur: true)
            ->required(),
            Forms\Components\TextInput::make('quantity')
            ->label(__('filament::resources/products.form.options.quantity'))
            ->numeric()
            ->required(),
 
            Forms\Components\Select::make('option')
            ->label(__('filament::resources/products.form.options.option'))
            ->options(fn (Get $get): Collection => Option::query()
            ->where('product_id', $get('title'))
            ->pluck('value', 'id'))
            ->live()
            ->visible(function(Get $get){
                $options = Option::where('product_id', $get('title'))->count();
                if($options>0){
                    return true;
                }else{
                    return false;
                }
            }),
            Forms\Components\TextInput::make('price')
                ->label(__('filament::resources/orders.form.price'))
                ->required()
                ->numeric()
                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/']),

            Forms\Components\Textarea::make('comment')
                ->label(__('filament::resources/orders.form.comment')),

            Forms\Components\TextInput::make('tentative')
                ->label('Tentative'),
           ]),
            Forms\Components\Section::make()
           ->schema([
                Forms\Components\Select::make('status')
                    ->label(__('filament::resources/orders.form.status'))
                    ->options([
                        'Pas encore confirmé' => 'Pas encore confirmé',
                        'Confirmé' => 'Confirmé',
                        'Appel en cours' => 'Appel en cours',
                        'En cours' => 'En cours',
                        'Rejeté' => 'Rejeté',
                        'Annulé' => 'Annulé',
                        'Reporté' => 'Reporté',
                        'Retour' => 'Retour',
                        'Pas de réponse' => 'Pas de réponse',
                        'Doublon' => 'Doublon',
                        'Livré' => 'Livré'
                    ])
                    ->hidden(function(){
                        if(!(auth()->user()->hasRole('Livreur') || auth()->user()->hasRole('super_admin'))){
                            return true ; 
                            }
                        })
                    ->live()
                    ->required(),
                Forms\Components\DateTimePicker::make('postponed_date')->label(__('filament::resources/leads.form.postponed_date'))
                        ->visible(function(Get $get){
                        $status = $get('status'); 
                        if($status == 'Reporté'){
                            return true;
                        }
                }),
           ])
        ->columnSpan(['lg' => 2])
        ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                ->label(__('filament::resources/orders.form.created_at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('reference')->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->label(__('filament::resources/orders.form.status'))
                ->badge()
                ->colors([
                    'primary' => 'Not yet confirmed',
                    'indigo' => 'Postponed',
                    'info' => 'Calling',
                    'cyan' => 'Processing',
                    'success' => 'Confirmed',
                    'purple' => 'Rejected',
                    'danger' => 'Canceled',
                    'warning' => 'No Answer',
                    'red' => 'Duplicated'
                ]),
                Tables\Columns\TextColumn::make('product.title')
                ->label(__('filament::resources/products.heading.singular')),

                Tables\Columns\TextColumn::make('number')
                ->label(__('filament::resources/orders.form.phone'))
                ->searchable(),
                Tables\Columns\TextColumn::make('name')
                ->label(__('filament::resources/orders.form.name'))
                ->limit(10)
                ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                ->label(__('filament::resources/orders.form.country')),
                Tables\Columns\TextColumn::make('city')
                ->label(__('filament::resources/orders.form.city'))
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')->label(__('filament::resources/products.form.options.quantity')),
                Tables\Columns\TextColumn::make('price')->label(__('filament::resources/orders.form.price'))->money('xof'),
                
                Tables\Columns\TextColumn::make('user')->label(__('filament::resources/users.heading.singular'))              
                ->formatStateUsing(fn($state) => $state->firstname . ' ' . $state->lastname),
            ])
            ->modifyQueryUsing(
                function (Builder $query) {
                    if (auth()->user()->hasRole('Seller')) {
                        $query->where('user_id', '=', auth()->id())->orderBy('created_at', 'desc');
                    }
                    if (auth()->user()->hasRole('Livreur')) {
                        $query->where('delivery_id', '=', auth()->id())->orderBy('created_at', 'desc');
                    }
                    
                    else {
                        return $query->orderBy('created_at', 'desc');
                    }
                }
            )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('assign')
                    ->label('Assign commande')
                    ->form([ 
                            Select::make('delivery_id')
                            ->label('Livreur')
                            ->options(function(){
                                $options = User::whereHas('role', function($query) {
                                    $query->where('name', 'Livreur');
                                })
                                ->get()
                                ->map(function($user) {
                                    return [
                                        'id' => $user->id,
                                        'fullName' => $user->firstname . ' ' .$user->lastname,
                                    ];
                                })
                                ->pluck('fullName', 'id')
                                ->toArray();
                                
                                return $options;
                            })
                            ->placeholder(__('Séléctionner le livreur...'))
                            ->required(),
                        
                    ])
                    ->action(function (array $data, Collection $records): void {
                        foreach ($records as $record){
                        $record->update([
                            'delivery_id' => $data['delivery_id']
                        ]);
                        }
                    })
                    ->hidden(function(){
                        if(! auth()->user()->hasRole('super_admin') ){
                            return true;
                        }
                    }),
                    Tables\Actions\BulkAction::make('ajouter_au_bon_de_livraison')
                    ->label('Ajouter au bon de livraiosn')
                    ->action(function (Collection $records) {
                        foreach ($records as $record) {
                            $record->in_bl = 'Y';
                            $record->save();
                        }
                        $content = request()->getContent();
                        $updates = json_decode($content);
                        
                        // new delivery note 
                        OrderResource::sendToDelivery($records);
                    })
                    ->hidden(function(){
                        if(auth()->user()->hasRole('Seller')){
                            return true;
                        }
                    })
                    ->icon('heroicon-s-truck')
                    ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function sendToDelivery($records)
    {
        $tk = "Bon-livraison" . Carbon::now()->format('m-d') . '-' . Str::random(3) . "-" . random_int(0, 11111);

        // Get the seller ID from the first order
        $firstOrder = Order::find($records[0]->id);
        $sellerId = $firstOrder->user_id;
        $country = $firstOrder->country_id;
        $orders_tn = array();
        foreach ($records as $record) {
            $order = Order::find($record->id);
                $order->update([
                    'in_bl' => 'Yes'
                ]);
                $check = DeliveryNote::where('user_id', $sellerId)
                    ->whereJsonContains('orders_tn', $order->id)
                    ->get();
                if (empty($check)) {
                    Notification::make()
                        ->title('La commande : ' . $order->reference . 'Déjà en livraison')
                        ->danger()
                        ->send();
                    return;
                } else {
                    array_push($orders_tn, $order->id);

                }
            }

        $delivery_note = new DeliveryNote();
        $delivery_note->create([
            'reference' => $tk,
            'user_id' => $sellerId,
            'type' => 'BL',
            'orders_tn' => json_encode($orders_tn),
            'nbr_orders' => count($records),
        ]);
        Notification::make()
            ->title('Le bon de livraison est crée avec succes')
            ->body(count($records) . 'records')
            ->success()
            ->send();
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'import' => Pages\importOrders::route('/import'),
            'scann' => Pages\QrScan::route('/qrcode/scann'),
        ];
    }
}
