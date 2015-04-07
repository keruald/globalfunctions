<?php

namespace Keruald;

/**
 * Keruald, core libraries for Pluton and Xen engines.
 * 
 * Global functions
 */
 
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
