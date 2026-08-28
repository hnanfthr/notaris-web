<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi kebalikannya
    public function archives()
    {
        return $this->belongsToMany(Archive::class, 'archive_client');
    }
}