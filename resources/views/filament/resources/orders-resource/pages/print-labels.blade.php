<x-filament-panels::page>
    <style>
        p{
            font-size: 14px;
        }
        #logo{
            margin: -25px !important;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
         .col-lg-6{
            padding-left: 15px;
            padding-right: 15px;
            position: relative;
            width: 100%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        [dir ='rtl'] body{
            text-align: right !important ;
        }
        /* [dir='rtl'] .pl-5{
            padding-right: 0rem !important;
            padding-left: 1rem !important;
        } */
        [dir= 'rtl'] .text-left{
            text-align: right !important;
        }
        .btn{
            display: block;
        }
        .qr{
            display: block !important;
            margin-left: auto !important;
            margin-right: 2em !important;
            margin-top: 0.5em;
        }
        .order-label{
            border:2px solid black;
            padding: 10px;
        }
        .line{
            border-top:1px solid black; 
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .text-large{
            font-size: larger;
        }

        @media print{
            @page {
                size: A4; /* Set the paper size to A4 */
                margin: 25px; /* Remove default margins */
                transform: scale(0.90); /* Set the scaling to 92% */
                transform-origin: top left; /* Set the origin of scaling */
            }
            .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
         .col-lg-6{
            padding-left: 15px;
            padding-right: 15px;
            position: relative;
            width: 100%;
            flex: 0 0 50%;
            max-width: 50%;
        }
            .mb-3{
                margin-bottom:1rem !important; 
            }

            .btn {
                display: none !important;
            }
        }
    </style>
    <style>
        .address{
            height: 38px;
        }
       
        @media print{
            @page{
            size: A4 landscape !important;
            }
            body{
                visibility: hidden;
            }
            .labels{
                visibility: visible !important;
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
            }
            .four{
                /* max-height: 100% !important; Four labels per column */
                min-height: 360px !important;
                max-height: 360px !important; /* Four labels per column */
            }
            .four p{
                font-size: 14px !important;
            }
            .qr{
                display: block !important;
                margin-left: auto !important;
                margin-right: 2em !important;
                margin-top: 0.5em !important;
                height: 43px !important;
                width: 43px !important;
            }
            .address{
                height: 38px !important;
                white-space: pre-line !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            .mt-3{
                margin-top: 1rem !important;
            }
            .mb-3{
                margin-bottom:1rem !important; 
            }
        }
    </style>
   
    
    <x-filament::button  @class(['inline' , 'ml-auto']) icon='heroicon-o-printer' onclick="printPage()">
        {{__('filament::resources/deliveryNotes.actions.print')}}
    </x-filament::button>

    <div class="labels row  my-2">
        @for ($i = 0; $i <count($orders) ; $i++)                
            <div class="col-lg-6 mb-2 four ">
                <div class="order-label  four ">
                    <div>
                        <div class="row mb-2">
                            <div class="col-lg-6 text-left pl-5">
                                <p class="mb-1"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.date')}}:</span> {{$delivery_note->created_at->format('Y-m-d')}} </p>
                                <h4 class="text-left">{{$orders[$i]->reference}}</h4>

                            </div>
                            <div class="col-lg-6 text-left pl-5">
                                @php
                                $qrCode = DNS2D::getBarcodePNG($orders[$i]->reference, 'QRCODE');
                                @endphp
                                <img class="qr" src="data:image/png;base64,{{$qrCode}}">
                            </div>
                        </div>
                    </div>
                   
                    <div>
                        <div class="flex">
                            <div class="col-lg-6">
                                <p class="mb-1"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.fullname')}}:</span> {{$orders[$i]->name}}</p>
                                <p class="mb-1"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.phone')}}:</span> {{$orders[$i]->number}}</p>
                                <p class="mb-1"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.city')}} :</span> {{$orders[$i]->city}}</p>
                                <p class="mb-2 address" ><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.address')}} :</span> {{$orders[$i]->address}}</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="mb-1"><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.products')}} :</span>
                                    <span class="products"> 
                                    @php
                                       
                                        $id=$orders[$i]->product_id;
                                        $product = \App\Models\Product::find($id);
                                        echo  $product->title .',';
                                        
                                    @endphp
                                    </span>
                                </p>
                                <p class="mb-1 "><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.quantity')}}:</span> {{$orders[$i]->quantity}}</p>
                                <p class="mb-1 font-bold"><span>{{__('filament::resources/deliveryNotes.deliery_notes.form.price')}}:</span><span class="text-large"> {{$orders[$i]->price}} {{$country->currency}} </span></p>
                               
                            </div>
                        </div>
                        <div class="row line">
                            <div class="col-lg-6 mt-3">
                                <p ><span class="font-bold">{{__('filament::resources/deliveryNotes.deliery_notes.form.comment')}}:</span> {{$orders[$i]->comment}}</p>
                            </div>
                            
                        </div>
                       
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <script>
        function printPage() {
            window.print();
        }
    </script>

</x-filament-panels::page>
