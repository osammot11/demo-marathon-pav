<?php
namespace App\Models;
use App\Models\Concerns\BelongsToDemo;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use BelongsToDemo;
    protected $fillable = ['code', 'amount'];
}
