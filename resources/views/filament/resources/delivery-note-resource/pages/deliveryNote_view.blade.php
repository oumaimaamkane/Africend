<x-filament-panels::page>
    @php
        $record = $this->infoList->record;
        $tn = json_decode($record->orders_tn);
        $orders = array();
        $id = 0;
        $total_price =0;
        foreach ($tn as  $value) {
            // $order = App\Models\Order::join('leads' , 'leads.id' , '=' ,'orders.lead_id')->join('stores' , 'stores.id' , '=' , 'leads.store_id')
            // ->where('orders.id' , '=' , $value)->first(); 
            $order = App\Models\Order::where('id' , '=' , $value)->first(); 
            
            $total_price += $order->price;
            array_push($orders , $order);
        }
        $id= $tn[0];
        $order = App\Models\Order::find($id);
        $country = App\Models\Country::find($order->country_id);
        $user = App\Models\User::find($record->user_id);
    @endphp
    <style>
        #logo{
            margin: -25px !important;
        }
        .justify-around{
            justify-content: space-around;
        }
        .mt-10{
            margin-top: 2.5rem !important;
        }
        .w-full{
            width: 100%;
        }
        @media print{
            table tr td , table tr th{
                border: 1px solid black;
            }
            body {
            visibility: hidden;
            }
            #fi-section {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
            }
            aside{
                visibility: hidden !important;
            }
            .ring-1{
                --tw-ring-shadow :none;
            }
            button{
                display: none !important;
            }
            .rounded-xl{
                border-radius: 0% !important;
            }

        }
    </style>

    <div id='fi-section' @class(['fi-section', 'rounded-xl','bg-white' ,'shadow-sm', 'ring-1', 'ring-gray-950/5' ,'dark:bg-gray-900', 'dark:ring-white/10' , 'p-6'])>
        <div class="card-body mt-3">
            <div @class(['flex'])>
                <h1 class="text-2xl mr-auto pt-2"><b>Bon de livraison</b></h1>
                
                <div class="mb-5 ml-auto">
                    <h2 class="h3 mb-2 font-bold">{{$record->reference}}</h2>
                    <p class="mb-2 text-sm"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.created_at')}} :</span> {{$record->created_at->format('Y-m-d')}}</p>
                    <p class="mb-2 text-sm"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.nbr_orders')}} :</span> {{$record->nbr_orders}}</p>
                    <p class="mb-2 text-sm"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.country')}} :</span> {{$country->name}}</p>
                </div>
                    
               
            </div>
            <hr class="mg-b-40">

            <div @class(['fi-ta-ctn', 'divide-y' ,'divide-gray-200', 'overflow-hidden' ,'rounded-xl' ,'bg-white', 'shadow-sm' ,'ring-1' ,'ring-gray-950/5', 'dark:divide-white/10' ,'dark:bg-gray-900' ,'dark:ring-white/10'])>
                <table @class(['table-auto','fi-ta-table' ,'w-full' ,'divide-y' ,'divide-gray-200', 'text-start' ,'dark:divide-white/5'])>
                    <thead @class(['bg-gray-50', 'dark:bg-white/5'])>
                        <tr>
                            <th @class(['fi-ta-header-cell', 'px-3','text-sm' ,'py-3.5' ,'sm:first-of-type:ps-6' ,'sm:last-of-type:pe-6'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.tracking_nbr')}}</th>
                            <th @class(['fi-ta-header-cell', 'px-3', 'text-sm' ,'py-3.5' ,'sm:first-of-type:ps-6' ,'sm:last-of-type:pe-6'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.fullname')}}</th>
                            <th @class(['fi-ta-header-cell', 'px-3', 'text-sm' ,'py-3.5' ,'sm:first-of-type:ps-6' ,'sm:last-of-type:pe-6'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.phone')}}</th>
                            <th @class(['fi-ta-header-cell', 'px-3', 'text-sm' ,'py-3.5' ,'sm:first-of-type:ps-6' ,'sm:last-of-type:pe-6'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.city')}}</th>
                            <th @class(['fi-ta-header-cell', 'px-3', 'text-sm' ,'py-3.5' ,'sm:first-of-type:ps-6' ,'sm:last-of-type:pe-6'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.unit_price')}}</th>
                        </tr>
                    </thead>
                    <tbody @class(['divide-y', 'divide-gray-200', 'whitespace-nowrap' ,'dark:divide-white/5'])>
                        @foreach($orders as $order)
                            <tr @class(['fi-ta-row', 'text-sm' ,'transition', 'duration-75', 'hover:bg-gray-50', 'dark:hover:bg-white/5'])>
                                <td @class(['fi-ta-cell', 'px-3' ,'py-4', 'text-center' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1'])>{{$order->reference }}</td>
                                <td @class(['fi-ta-cell', 'px-3' ,'py-4','text-center' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1'])>{{$order->name}}</td>
                                <td @class(['fi-ta-cell', 'px-3' ,'py-4','text-center' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1'])>{{$order->number}}</td>
                                <td @class(['fi-ta-cell', 'px-3' ,'py-4','text-center' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1'])>{{$order->city}}</td>
                                <td @class(['fi-ta-cell', 'px-3' ,'py-4','text-center' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1'])> {{ $order->price }}</td>
                            </tr>

                        @endforeach                  
                        <tr>
                            <td colspan="3" rowspan="5"></td>
                            <td @class(['text-center', 'capitalize', 'font-bold'])>{{__('filament::resources/deliveryNotes.deliery_notes.form.total')}}</td>
                            <td @class(['fi-ta-cell', 'px-3' , 'py-4' ,'first-of-type:ps-1','last-of-type:pe-1', 'sm:first-of-type:ps-3' ,'sm:last-of-type:pe-3 w-1']) colspan="3">
                                <h4 class="font-bold">{{$total_price}} {{$country->currency}}</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div @class(['flex' , 'flex-row' , 'justify-around' , 'mt-10'])>
                <h4 >{{__('filament::resources/deliveryNotes.deliery_notes.form.sender')}}:</h4>
                <h4>{{__('filament::resources/deliveryNotes.deliery_notes.form.receiver')}}:</h4>
            </div>
        </div>
        <div class="card-footer text-right border-0" style="margin-top: 10em;">
            <x-filament::button  icon='heroicon-o-printer' onclick="printPage()">
                {{__('filament::resources/deliveryNotes.actions.print')}}
            </x-filament::button>
        </div>
    </div>
    <script>
        function printPage() {
            var originalTitle = {!! json_encode($record->reference) !!};
            document.title = originalTitle; // Set a temporary title for printing
            window.print();
        }
    </script>    
</x-filament-panels::page>
