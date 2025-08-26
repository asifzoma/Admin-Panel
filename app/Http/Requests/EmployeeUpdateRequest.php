<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends EmployeeStoreRequest
{
    // By extending EmployeeStoreRequest, we inherit its rules and authorize methods.
    // If you had different rules for updating, you would override the rules() method here.
}
