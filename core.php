<?php

namespace Keruald;

use Keruald\OmniTools\Network\IP;
use Keruald\OmniTools\Strings\Multibyte\StringUtilities;

/**
 * Keruald, core libraries for Pluton and Xen engines.
 *
 * Global functions
 */

///
/// Strings
///

/**
 * Pads a multibytes string to a certain length with another string
 *
 * @param string $input the input string
 * @param int $pad_length the target string size
 * @param string $pad_string the padding characters (optional, default is space)
 * @param int $pad_type STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH (optional, default is STR_PAD_RIGHT)
 * @param string $encoding the character encoding (optional)
 *
 * @return string the padded string
 * @deprecated Use Keruald\OmniTools\Strings\Multibyte\StringUtilities::pad
 */
function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = null) {
    return StringUtilities::pad($input, $pad_length, $pad_string, $pad_type, $encoding);
}

/**
 * Determines whether the specified is a valid IP address
 *
 * @param string $string the string to validate as an IP
 * @return bool true if the specified string is a valid IP address; otherwise, false
 * @deprecated Use Keruald\OmniTools\Network\IP::isIP
 */
function is_ip ($string) {
    return IP::isIP($string);
}

/**
 * Determines whether the specified is a valid IPv4 address
 *
 * @param string $string the string to validate as an IP
 * @return bool true if the specified string is a valid IPv4 address; otherwise, false
 * @deprecated Use Keruald\OmniTools\Network\IP::isIPv4
 */
function is_ipv4 ($string) {
    return IP::isIPv4($string);
}

/**
 * Determines whether the specified is a valid IPv6 address
 *
 * @param string $string the string to validate as an IP
 * @return bool true if the specified string is a valid IPv6 address; otherwise, false
 * @deprecated Use Keruald\OmniTools\Network\IP::isIPv6
 */
function is_ipv6 ($string) {
    return IP::isIPv6($string);
}


///
/// Identifiers
///

/**
 * Generates a RFC 4211 compliant v4 UUID (random-based)
 *
 * @return string the UUID
 */
function uuid () {
    //Code by Andrew Moore
    //See http://php.net/manual/en/function.uniqid.php#94959
    //    https://www.ietf.org/rfc/rfc4122.txt

    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

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
 * @param string $value the header value
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
 * which takes in consideration proxy values, blindly trusted.
 *
 * @return string the remote address
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
