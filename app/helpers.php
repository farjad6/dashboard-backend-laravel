<?php
use Illuminate\Support\Facades\Log;

if (! function_exists('customExceptionError')) {
    function customExceptionError( $data ) {
        Log::error("customExceptionError");
        Log::error(print_r($data, true));
    }
}