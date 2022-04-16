<?php namespace Sarga\API\Http\Requests;

use Webkul\Core\Contracts\Validations\Address;
use Webkul\Core\Contracts\Validations\AlphaNumericSpace;
use Webkul\Core\Contracts\Validations\PhoneNumber;
use Webkul\Customer\Http\Requests\CustomerAddressRequest;
use Webkul\Customer\Rules\VatIdRule;

class RecipientRequest extends CustomerAddressRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'   => ['required'],
            'last_name'    => ['required'],
            'phone'        => ['required', new PhoneNumber],
        ];
    }
}