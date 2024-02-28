<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Option;
class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data) :array{
        $data['user_id'] = auth()->id();
        return $data;
        
    }

    protected function afterCreate(){
        $data = $this->data;
        // save the options
        $product_id = $this->record->getKey();

        $variants = $data['options'];
        foreach($variants as $variant){
            $option = new Option([
                'product_id' => $product_id,
                'option' => $variant['option'],
                'value' => $variant['value'],
                'qte' => $variant['qte'],
            ]);
            $option->save();
           
        }

        
    }
}
