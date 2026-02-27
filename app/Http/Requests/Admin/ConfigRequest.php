<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'logo' => 'image',
            'logo_admin' => 'image',
            'metaimg' => 'image',
            'favicon' => 'image',
            'imgheader' => 'image',
            'watermark' => 'image',
      ];
    }
}
