<?php

// app\Models\User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Campos ocultos en la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     * (No incluimos 'password' => 'hashed' para no doble-hashear,
     *  ya que en el controlador usas Hash::make()).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Requerido por JWTSubject.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
public function proyectosCreados()
{
    return $this->hasMany(Proyecto::class, 'created_by');
}

    /**
     * Requerido por JWTSubject.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
