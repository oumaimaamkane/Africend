<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Permission\Models\Role;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles , HasPanelShield;
    
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'city',
        'address',
        'activity_area',
        'bank_name',
        'bank_rib',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFilamentName(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function role(){
        
        return $this->belongsTo(Role::class);
    }
    public function product(){
        
        return $this->hasMany(Product::class);
    }
    // public function lead(){
        
    //     return $this->hasMany(Lead::class);
    // }

    public function canAccessPanel(Panel $panel): bool
    {
            if ($panel->getId() === 'admin') {
                return $this->hasRole('super_admin');
            }

            if ($panel->getId() === 'seller') {
                if($this->role_id == 2){
                    return $this->hasRole('Seller');
                }
            }


            if ($panel->getId() === 'livreur') {
                if($this->role_id == 6){
                    $this->assignRole('Livreur');
                    return true;
                }
            }

      
       return false;

    }
}
