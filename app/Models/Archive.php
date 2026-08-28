<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Archive extends Model
{
    use HasFactory;

    protected $guarded = [];

    // --- TAMBAHKAN INI ---
    protected $casts = [
        'checklist_items' => 'array', // Biar otomatis jadi array pas diambil
    ];
    // ---------------------

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'archive_client');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}