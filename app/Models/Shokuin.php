<?php

namespace App\Models;

// app/Models/Shokuin.php
use Illuminate\Foundation\Auth\User as Authenticatable;

class Shokuin extends Authenticatable
{
    protected $table = 'shokuinmst';
    protected $primaryKey = 'hsid';

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'hsid','name2','email','password'
    ];

    protected $hidden = ['password'];
}

