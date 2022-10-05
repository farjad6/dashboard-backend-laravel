<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Auth;

class invite_store_access implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() { }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if( $value > 4 ){
            return false;
        }

        if( Auth::user()->role == 1 || Auth::user()->role == 2 ){
            if( Auth::user()->role == 2 && ( $value == 1 || $value == 2 ) ){
                return false;
            }

            return true;
        }


        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Restricted Access';
    }
}
