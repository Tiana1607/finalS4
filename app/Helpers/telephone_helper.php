<?php

if (!function_exists('formaterTelephone')) {
    function formaterTelephone(string $telephone): string
    {
        $tel = preg_replace('/\s+/', '', $telephone);
        if (strlen($tel) !== 10) {
            return $telephone;
        }
        return substr($tel, 0, 3) . ' ' . substr($tel, 3, 2) . ' ' . substr($tel, 5, 3) . ' ' . substr($tel, 8, 2);
    }
}
