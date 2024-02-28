<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\User;
use App\Models\Order;
use App\Models\Invc;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;

class generateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the start and end dates for the date range (last Thursday until next Wednesday)
       
        $invoiceDate = Carbon::now();
        $start = $invoiceDate->copy()->previous('Saturday');  
        $end = $invoiceDate->copy()->subDay();    // Assuming today is Thursday

         // Get countries
        $countries = Country::all();
        foreach($countries as $country){
            $users = User::where('role_id' , '=' ,'3')->get();

            foreach ($users as $user) {                
                $user_orders=Order::where("user_id", "=", $user->id)
                ->where('country_id' , '=' , $country->id)
                ->whereIn('status', ['Livré', 'Retour'])
                ->whereRaw('DATE(updated_at) BETWEEN ? AND ?', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->get();

                if($user_orders->isNotEmpty()){
                    // Create Admin Instance;
                    $admin = new Party([
                        'custom_fields' => [
                            'pays'  => $country->name,
                            'total des commandes'  => $user_orders->count(),
                        ],
                    ]);
            
                    $customer = new Party([
                        'name'          => $user->firstname.' '. $user->lastname,
                        'bank'          => $user->bank_name,
                        'rib'           => $user->bank_rib,                       
                    ]);
    
                    $items = [];
                    $order_ids=[];
                    $total_delivered =0;

                    foreach($user_orders as $user_order){
                        $delivery_fees=0;
                        if ($user_order->status == 'Retour') {
                            $delivery_fees = $country->lead_returned_fees;
                        }else{
                            $delivery_fees = $country->lead_delivered_fees;
                        }

                        //get the price of th product
                            
                        $total_delivered += $delivery_fees;
                        $item = (new InvoiceItem())
                        ->reference($user_order->reference)
                        ->client_name($user_order->client_name)
                        ->city($user_order->client_city)
                        ->delivery_fees($delivery_fees)
                        ->pricePerUnit($user_order->selling_price)
                        ->quantity($user_order->quantity)
                        ->status($user_order->status);
                        $order_ids =[...$order_ids , $user_order->id];
                        array_push($items , $item);
    
                    }

                $date = Carbon::now();
                $invoice_nbr = $user->id.date_format($date , 'm').date_format($date , 'd').'-'.$country->name;
                $invoice = Invoice::make('Invoice',$invoice_nbr)
                    ->series('BIG')
                    ->status('Unpaid')
                    ->sequence(667)
                    ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                    ->seller($admin)
                    ->buyer($customer)
                    ->date(now())
                    ->dateFormat('m/d/Y')
                    ->payUntilDays(14)
                    ->currencySymbol('xof')
                    ->currencyCode('xof')
                    ->currencyFormat('{VALUE}{SYMBOL}')
                    ->currencyThousandsSeparator('.')
                    ->currencyDecimalPoint(',')
                    ->filename($customer->name)
                    ->addItems($items)
                    // You can additionally save generated invoice to configured disk
                    ->save('public');
                $check_invoice = Invc::where('user_id' , '=' , $user->id)
                ->where('reference' , '=' , $invoice_nbr)
                ->orWhere('amount' ,'=' , $invoice->total_amount)
                ->first();
                    if(!$check_invoice){
                        $total_amount_net = $invoice->total_amount - $total_delivered ;
                        $invoice_db = new Invc();
                        $invoice_db->create([
                            'user_id' => $user->id,
                            'country_id' => $country->id,
                            'orders_ids' => json_encode($order_ids),
                            'nbr_orders' => count($order_ids),
                            'reference' => $invoice_nbr,
                            'amount' => $invoice->total_amount,
                            'amount_net' => $total_amount_net,
                            'status' => 'Non Payé',
                            'created_at' => Carbon::now()
                        ]);
                    }
                    }

            }
        }
    }
}
