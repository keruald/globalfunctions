<?php

namespace Keruald;

use Keruald\OmniTools\Debug\Debugger;
use Keruald\OmniTools\Identifiers\UUID;
use Keruald\OmniTools\Network\IP;
use Keruald\OmniTools\HTTP\Requests\RemoteAddress;
use Keruald\OmniTools\HTTP\Requests\Request;
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
 * @deprecated Use Keruald\OmniTools\Identifiers\UUID::UUIDv4
 */
function uuid () {
    return UUID::UUIDv4();
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
    Debugger::printVariable($variable);
}

/**
 * Prints human-readable information about a variable, wrapped in a <pre> block
 * then dies
 *
 * @param mixed $variable the variable to dump
 */
function dieprint_r ($variable) {
    Debugger::printVariableAndDie($variable);
}

///
/// Client information
///

/**
 * Returns the full header or the IP part of it
 *
 * @param string $value the header value
 * @return string the IP part
 * @deprecated
 */
function extract_client_ip_from_header ($value) {
    return (new RemoteAddress($value))->getClientAddress();
}

/**
 * Gets remote IP address.
 *
 * This is intended as a drop-in replacement for $_SERVER['REMOTE_ADDR'],
 * which takes in consideration proxy values, blindly trusted.
 *
 * @return string the remote address
 * @deprecated Use Keruald\OmniTools\HTTP\Requests\Request::getRemoteAddress()
 */
function get_remote_addr () {
    return Request::getRemoteAddress();
}
