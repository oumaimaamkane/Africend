@php
    $order = '';
    if($this->reference){
        if(auth()->user()->hasRole('Livreur')){
            $order = \App\Models\Order::where('delivery_id' , '=' , auth()->id())
            ->where('reference' , '=' , $this->reference)
            ->first();
        }

    }
@endphp
<x-filament-panels::page>
    <style>
        fieldset{
            width: 100%;
        }
        video{
            width: 100%;
            transform: none !important; /* Flip horizontally (mirror effect) */
        }
        .fi-dropdown-panel{
            left: 49px !important;
            top: 111px !important;
            overflow: scroll !important;
            height: 190px !important;
        }
        #cameras{
            font-size: 8px;
        }
        .justify-arround{
            justify-content: space-around;
        }
        .flex-row{
            flex-direction: row;
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

    </style>
    <div id="video-container">
        <video id="preview" playsinline></video>
    </div>
    <div class="row">
        <x-filament::button id="start-button" class="col-lg-6">
            Start
        </x-filament::button >
        <x-filament::button id="stop-button" class="col-lg-6 ">
            Stop
        </x-filament::button>
    </div>
    <div class="row">
        <div class="col-lg-6 p-2">
            <h5>Preferred camera:</h5>
            <x-filament::input.wrapper>
                <x-filament::input.select id="cam-list">
                    <option value="environment" selected>Environment Facing (default)</option>
                    <option value="user">User Facing</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
        <div class="col-lg-6 p-2">
            <h5>Camera has flash: </h5>
            <x-filament::button id="flash-toggle">
                📸 Flash: <span id="flash-state">off</span>
            </x-filament::button>
            <div>
                <span id="cam-has-flash"></span>
            </div>
        </div>
    </div>

    <div>
    <h5>Detected QR code: </h5>
    <span id="cam-qr-result">None</span>
    </div>

    <div>
        @if($order && auth()->user()->hasRole('Livreur'))
            <x-filament::modal id="myModal">
                <x-filament::section>
                    <x-slot name="heading">
                        Order details
                    </x-slot>
                    <div>
                        <label><b>Client name: </b></label>
                        <span>{{$order->name}}</span>
                    </div>
                    <div>
                        <label><b>Client Number: </b></label>
                        <span>{{$order->number}}</span>
                    </div>
                    <div>
                        <label><b>Client City: </b></label>
                        <span>{{$order->city}}</span>
                    </div>
                    <div>
                        <label><b>Client Address: </b></label>
                        <span>{{$order->address}}</span>
                    </div>
                    <div>
                        <label><b>Quantity: </b></label>
                        <span>{{$order->quantity}}</span>
                    </div>
                    <div>
                        <label><b>Selling price: </b></label>
                        <span class="text-custom-600"><b>{{$order->price}}</b></span>
                    </div>
                </x-filament::section>

                <label >Shipment status</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model="status" id="status">
                        <option value="Livré">Livré</option>
                        <option value="Rejeté">Rejeté</option>
                        <option value="Annulé">Annulé</option>
                        <option value="Reporté">Reporté</option>
                        <option value="Pas de réponse">Pas de réponse</option>
                        <option value="Retour">Retour</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
                
                <div id="date" style="display: none;">
                    <label>Date</label>
                    <x-filament::input.wrapper>
                        <x-filament::input type='datetime-local' wire:model="postponed_date" id="postponed">
                        </x-filament::input>
                    </x-filament::input.wrapper>
                </div>

                <label>Comment</label>
                <textarea wire:model="comment" class="fi-fo-markdown-editorfi-input-wrp flex rounded-lg shadow-sm  border-none border-gray-600 ring-1 transition duration-75 bg-white focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500"></textarea>
                <x-filament::button wire:click="updateOrder">
                    effectuer
                </x-filament::button>
            </x-filament::modal>
        @endif
    </div>
    @push('scripts')
        <script type="module">
            import QrScanner from "../../../../js/app/qr-scanner.min.js";
        
            const video = document.getElementById('preview');
            const videoContainer = document.getElementById('video-container');
            const camList = document.getElementById('cam-list');
            const camHasFlash = document.getElementById('cam-has-flash');
            const flashToggle = document.getElementById('flash-toggle');
            const flashState = document.getElementById('flash-state');
            const camQrResult = document.getElementById('cam-qr-result');
        
            function setResult(label, result) {
                var qrcode = result.data;
                    var delivery = document.getElementById('delivery');
                    if(qrcode.includes('AP')){
                        var role = {!! json_encode(auth()->user()->hasRole('warehouse manager')) !!};
                        if(role == false){
                            @this.dispatch('open-modal', {
                            id: 'myModal'
                                });
                            @this.set('reference', qrcode);
                            
                        }else{
                            console.log('warehouse');
                            @this.dispatch('open-modal', {
                                id: 'warehouseModel'
                            });
                            @this.set('reference', qrcode);
                            var button = document.getElementById('updateOrderForWH');
                            button.addEventListener('click' , function(){
                                var delivery = document.getElementById('delivery');
                                @this.set('deliveryMan', delivery.value);

                            })
                        }
                    }else{
                        new FilamentNotification()
                        .title('reference n existe pas')
                        .danger()
                        .send()
                    }
                    var statusInput = document.getElementById('status');
                    statusInput.addEventListener('change' , function(){
                        var selectedValue = statusInput.value;
                        if(selectedValue == 'Reporté'){
                            console.log('selected');
                            let date = document.getElementById('date');
                            date.style.display = 'block';
                        }
                    });
                clearTimeout(label.highlightTimeout);
                // label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
            }
        
            // ####### Web Cam Scanning #######
        
            const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
                onDecodeError: error => {
                    camQrResult.textContent = error;
                    camQrResult.style.color = 'inherit';
                },
                highlightScanRegion: true,
                highlightCodeOutline: true,
            });
        
            const updateFlashAvailability = () => {
                scanner.hasFlash().then(hasFlash => {
                    camHasFlash.textContent = hasFlash;
                    flashToggle.style.display = hasFlash ? 'inline-block' : 'none';
                });
            };
        
            scanner.start().then(() => {
                updateFlashAvailability();
                // List cameras after the scanner started to avoid listCamera's stream and the scanner's stream being requested
                // at the same time which can result in listCamera's unconstrained stream also being offered to the scanner.
                // Note that we can also start the scanner after listCameras, we just have it this way around in the demo to
                // start the scanner earlier.
                QrScanner.listCameras(true).then(cameras => cameras.forEach(camera => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.text = camera.label;
                    camList.add(option);
                }));
            });
        
            // QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
        
            // for debugging
            window.scanner = scanner;
        
            
            scanner.setInversionMode('both');
        
            camList.addEventListener('change', event => {
                scanner.setCamera(event.target.value).then(updateFlashAvailability);
            });
        
            flashToggle.addEventListener('click', () => {
                scanner.toggleFlash().then(() => flashState.textContent = scanner.isFlashOn() ? 'on' : 'off');
            });
        
            document.getElementById('start-button').addEventListener('click', () => {
                scanner.start();
            });
        
            document.getElementById('stop-button').addEventListener('click', () => {
                scanner.stop();
            });
        
            
        </script>
      @endpush
</x-filament-panels::page>
