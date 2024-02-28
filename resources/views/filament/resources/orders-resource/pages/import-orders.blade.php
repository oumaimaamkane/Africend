<x-filament-panels::page>
    <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
    <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />
    <script src="https://jsuites.net/v4/jsuites.js"></script>
    <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

    <div id="spreadsheet"></div>
    <x-filament::button size='sm'  id="import" wire:click="import">
        {{__('filament::resources/orders.actions.upload')}}
    </x-filament::button>
@push('scripts')
<script>
    var countries ={!! json_encode($countries) !!};
    var products = {!! json_encode($products) !!};

    // Create the spreadsheet
    var mySpreadsheet =jspreadsheet(document.getElementById('spreadsheet'), {
    minDimensions:[9,15],
        defaultColWidth: 100,
        tableOverflow: true,
        csvHeaders:true,
        tableHeight: "500px",
        tableWidth: "100%",
        selectionCopy: true,
        allowInsertColumn:false,
        allowInsertRow:true,
        allowRenameColumn:false,
        allowDeleteRow:true,
        allowDeleteColumn:false,
        autoIncrement:false,
        saveAs:true,
        columns: [
            {
                name : 'country_id',
                type: 'dropdown',
                autocomplete: true,
                title : "{{trans('filament::resources/orders.form.country')}}",
                width:150,
                lazyLoading: true,
                source:countries
            },
            { 
                name : "name",
                type: 'text',
                title:"{{trans('filament::resources/orders.form.name')}}",
                width:150
            },
            {
                name : "phone",
                type: 'text',
                title:"{{trans('filament::resources/orders.form.phone')}}",
                width:150
            },
            
            {
                name : "city",
                type: 'text',
                title:"{{trans('filament::resources/orders.form.city')}}",
                width:150,
            },
            {
                name : "address",
                type: 'text',
                title : "{{trans('filament::resources/orders.form.address')}}",
                width:150,
            },
            
            {
                name : 'product_name',
                type: 'dropdown',
                title:"{{trans('filament::resources/products.heading.plural')}}",
                width:150,
                multiple:false,
                autocomplete: true,
                lazyLoading: true,
                source:products
            },
            {
                name : "quantity",
                type: 'numeric',
                title : "{{trans('filament::resources/products.form.options.quantity')}}",
                width:150,
            },
            {
                name : "price",
                type: 'numeric',
                title : "{{trans('filament::resources/orders.form.price')}}",
                width:250,
            },
            {   
                name : 'lead_date',
                type: 'calendar',
                title:"{{trans('filament::resources/orders.form.created_at')}}",
                width:150
            },
        ],
        onload: onload,

    });

    var button = document.getElementById('import');
    button.addEventListener('click' , function(){
        var jsonData = mySpreadsheet.getJson();
        // Trigger the Livewire method with the JSON data
        @this.set('jsonData', jsonData);
        new FilamentNotification()
            .title("Les commandes ont été importés avec succès")
            .success()
            .send();
        location.reload();
    });

</script>
@endpush
</x-filament-panels::page>
