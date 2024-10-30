<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios'; // Especificar el nombre de la tabla si no sigue la convención

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'surname',
        'age',
        'isAdmin',
        'permittedLessons',
        'image',
    ];

    protected $hidden = [
        'password', // Para ocultar la contraseña cuando se convierta a un arreglo o JSON
        'remember_token',
    ];

    // Puedes agregar más métodos y relaciones según tus necesidades
}
