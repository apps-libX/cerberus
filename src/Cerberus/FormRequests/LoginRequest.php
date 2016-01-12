<?php
/**
 * LoginRequest.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:43.
 */

namespace Cerberus\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|min:4|max:254',
            'password' => 'required|min:6'
        ];
    }
}
