<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class States extends Model
{
    use HasFactory;
    protected $table = 'states';

     protected $fillable = [
       'name', 
       'country_id'
    ];
}
