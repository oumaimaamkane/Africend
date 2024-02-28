<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'fluentui-production-20-o';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Step 1')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                        ->label(__('filament::resources/products.form.title'))
                            ->required(),
                        Forms\Components\TextInput::make('price')
                        ->label(__('filament::resources/products.form.price'))
                        ->numeric()
                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        ->required(),
                        Forms\Components\MarkdownEditor::make('description')
                        ->label(__('filament::resources/products.form.description'))
                            ->required()
                            ->columnSpan('full'),

                        Forms\Components\FileUpload::make('image')
                        ->label(__('filament::resources/products.form.image'))
                        ->image()
                        ->multiple()
                        ->disk('public')
                        ->directory('products')
                        ->preserveFilenames()
                        ->required(),
                        ]),
                    Wizard\Step::make('Step 2')
                        ->schema([
                            Forms\Components\TextInput::make('initial_quantity')
                            ->label(__('filament::resources/products.form.initial_quantity'))
                            ->numeric()
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                            ,
                            Repeater::make('options')
                            ->label(__('filament::resources/products.form.options.label'))
                            ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('option')->label(__('filament::resources/products.form.options.option'))->placeholder('Ex: color , size ..'),
                                    Forms\Components\TextInput::make('value')->label(__('filament::resources/products.form.options.value')),
                                    Forms\Components\TextInput::make('qte')->label(__('filament::resources/products.form.options.quantity'))->numeric(),
                                        Repeater::make('subOption')
                                        ->label(__('filament::resources/products.form.options.sub_options'))
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\TextInput::make('sub_option')->label(__('filament::resources/products.form.options.sub_option')),
                                            Forms\Components\TextInput::make('value')->label(__('filament::resources/products.form.options.value')),
                                            Forms\Components\TextInput::make('qte')->label(__('filament::resources/products.form.options.quantity'))
                                            ->numeric(),
                                            ])
                                        ->columns(3)
                                        ->maxItems(1000)
                                        ->collapsible()
                                        ->columnSpanFull()
                                        ->defaultItems(0)
                                    ])
                                ->columns(3)
                                ->defaultItems(0)
                                ->maxItems(1000)
                                ->collapsible(),
                            Forms\Components\Select::make('country_id')
                            ->label(__('filament::resources/products.form.country'))
                                ->relationship('country', 'name')
                                ->required(),
                            Forms\Components\Select::make('status')
                            ->label(__('filament::resources/products.form.status'))
                            ->options([
                                'In hold' => __('filament::resources/products.form.In hold'),
                                'Picked up' => __('filament::resources/products.form.Picked up'),
                                'Shipped' => __('filament::resources/products.form.Shipped'),
                                'In warehouse' =>__('filament::resources/products.form.In warehouse')
                            ])
                            ->visible(function(){
                                if(auth()->user()->hasRole('super_admin')){
                                    return true;
                                }
                            })
                            ->default('In hold'),
                        ]),
                   
                ])->columnSpanFull()
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                    >
                        Submit
                    </x-filament::button>
                BLADE)))
            ])
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->label(__('filament::resources/products.form.image'))
                ->disk('public')
                ->size('50px')->circular()->stacked()->limit(1),
                Tables\Columns\TextColumn::make('title')
                ->label(__('filament::resources/products.form.title'))
                ->limit(20)->searchable(),
                               
                Tables\Columns\TextColumn::make('initial_quantity')
                ->label(__('filament::resources/products.form.initial_quantity'))
                ,
               
                Tables\Columns\TextColumn::make('price')
                ->label(__('filament::resources/products.form.price'))
                ->money('xof'),
                
                Tables\Columns\TextColumn::make('status')
                ->label(__('filament::resources/products.form.status'))
                ->badge()
                ->colors([
                    'primary' => 'In hold',
                    'secondary' => 'Picked up',
                    'purple' => 'Shipped',
                    'success' =>'In warehouse'
                ])->hiddenOn('create'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
