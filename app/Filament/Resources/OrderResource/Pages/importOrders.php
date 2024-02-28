<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use App\Models\Country;
use App\Models\Product;
use Str;
class importOrders extends Page
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.resources.orders-resource.pages.import-orders';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';
    protected static ?int $navigationSort = 5;



    public static function getNavigationLabel(): string
    {
        return __('filament::resources/orders.form.importLeads');
    }
    public  function getTitle(): string
    {
        return __('filament::resources/orders.form.importLeads');
    }


    public $countries , $products , $jsonData;
    public function mount() {
        $countries = Country::all();
        $products = Product::where('user_id' , '=' , auth()->id())->where('status' ,'=' ,'In warehouse')->get();

        $country = array();
        $product = array();
        foreach($countries as $item){
            array_push($country , $item->name);
        }
        foreach($products as $item){
            array_push($product , $item->title);
        }

        $this->countries = $country;
        $this->products = $product;
    }

    public function import(){
        $rows = $this->jsonData;
        $success = 0;
        $error =0;
        if(count($rows)>0){
            foreach($rows as $row){
                if(!empty($row['name']) && !empty($row['number'])){                                                    
                    $country=Country::where('name' , 'LIKE' , $row['country'])->first();
                    $product = $row['product_name'];
                    $quantity =$row['quantity'];
                    
                    $check_product = Product::where('title' ,'Like' , $product)->first();
                                                                  
                    $refrence =  Str::random(3)."-". random_int(0 , 11111);                 

                    $check_lead = Order::where('user_id' , '=' , auth()->id() )
                    ->where('number' , 'LIKE' ,"%{$row['number']}%")
                    ->where('city' , '=' , $row['city'])
                    ->where('product_id','=',json_encode($product))
                    ->first();

                    if($check_lead){
                        Notification::make()
                       ->title("Certains commandes sont déjà insérés")
                       ->danger()
                       ->send(); 
                    }else{
                        $lead = new Order();
                        $lead->create([
                            'reference' => $refrence,
                            'user_id' => auth()->id(),
                            'product' =>$product,
                            'country_id' => $country->id,
                            'name' => $row['name'],
                            'number' => $row['number'],
                            'city' => $row['city'],
                            'address' => $row['address'],
                            'quantity' => $quantity,
                            'price' => $row['lead_price'],
                            'created_at' => $row['lead_date'],
                        ]);
                        if($lead){
                            $success +=1;
                        }
                        else{
                                $error +=1;
                            }
                    }
                    
                    
                }
            }
        }
        if($success>0){
            Notification::make()
            ->title("Commandes ont été importés avec succès")
            ->success()
            ->send();
            $admins = User::where('role_id' , '=' , 1)->get();
            $seller = User::find(auth()->id());
            //seller fullname
            $fullname = $seller->firstname .' ' . $seller->lastname;
    
            Notification::make()
            ->success()
            ->title('Nouveau Commande')
            ->body("Nouveau commande est ajouté par $fullname.")
            ->icon('heroicon-o-shopping-cart')
            ->sendToDatabase($admins);
        }
        if($error >0){
            Notification::make()
            ->title("Quelque chose s'est mal passé")
            ->danger()
            ->send();
        }
    }
}
