<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee's full name.
     */
    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name).' '.ucfirst($this->last_name);
    }
}
