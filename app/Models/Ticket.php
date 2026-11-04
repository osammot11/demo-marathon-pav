<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDemo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory, BelongsToDemo;

    protected $guarded = [];

    // Un biglietto appartiene a un ordine
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
