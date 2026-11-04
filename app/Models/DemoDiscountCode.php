<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDemo;
use Illuminate\Database\Eloquent\Model;

class DemoDiscountCode extends Model
{
    use BelongsToDemo;
    protected $fillable = ['demo_slug', 'code', 'amount'];
}
