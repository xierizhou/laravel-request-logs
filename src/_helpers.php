<?php
use Rizhou\RequestLog\Helpers;


if (!function_exists('request_log_script')) {
    function request_log_script(): string
    {
        return Helpers::requireJs();
    }
}


