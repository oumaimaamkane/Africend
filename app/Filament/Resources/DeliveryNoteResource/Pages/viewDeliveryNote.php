<?php

namespace App\Filament\Resources\DeliveryNoteResource\Pages;

use App\Filament\Resources\DeliveryNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class viewDeliveryNote extends ViewRecord
{
    protected static string $resource = DeliveryNoteResource::class;
    protected static string $view = 'filament.resources.delivery-note-resource.pages.deliveryNote_view';

}
