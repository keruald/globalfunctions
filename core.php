<?php

namespace Keruald;

/**
 * Keruald, core libraries for Pluton and Xen engines.
 * 
 * Global functions
 */

///
/// Error and debug
///
 
/**
 * Prints human-readable information about a variable, wrapped in a <pre> block
 * 
 * @param mixed $variable the variable to dump
 */
function dprint_r ($variable) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}

/**
 * Prints human-readable information about a variable, wrapped in a <pre> block
 * then dies
 * 
 * @param mixed $variable the variable to dump
 */
function dieprint_r ($variable) {
    dprint_r($variable);
    die;
};

///
/// Client information
///

/**
 * Returns the full header or the IP part of it
 *
 * @param string $value The header value
 * @return string the IP part
 */
function extract_client_ip_from_header ($value) {
    if (strpos($value, ',') !== false) {
        //Header contains 'clientIP, proxyIP, anotherProxyIP'
        //The first value is so the one to return.
        //See draft-ietf-appsawg-http-forwarded-10.
        $ips = explode(',', $value, 2);
        return trim($ips[0]);
    }

    return $value;
}

/**
 * Gets remote IP address.
 *
 * This is intended as a drop-in replacement for $_SERVER['REMOTE_ADDR'],
 * which takes in consideration proxy values.
 */
function get_remote_addr () {
    $candidates = [
        //Standard header provided by draft-ietf-appsawg-http-forwarded-10
        'HTTP_X_FORWARDED_FOR',

        //Legacy headers
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_X_FORWARDED',

        //Default header if no proxy information could be detected
        'REMOTE_ADDR',
    ];

    foreach ($candidates as $candidate) {
        if (array_key_exists($candidate, $_SERVER)) {
            return extract_client_ip_from_header($_SERVER[$candidate]);
        }
    }

    return '';
}
