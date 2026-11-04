<?php
namespace App\Models;
use App\Models\Concerns\BelongsToDemo;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToDemo;
    protected $fillable = ['key', 'value'];
}
