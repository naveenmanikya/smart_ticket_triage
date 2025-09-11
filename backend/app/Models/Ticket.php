<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'subject',
        'body',
        'status',
        'category',
        'explanation',
        'confidence',
        'note',
    ];
}