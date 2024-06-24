<?php

/**
 * Generate api key
 */
function generate_api_key() {
    return bin2hex(random_bytes(16));
}