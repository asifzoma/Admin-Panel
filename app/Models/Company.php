<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'website',
        'logo',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
