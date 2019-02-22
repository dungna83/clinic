<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
}
