<?php

/**
 * Hash password using bcrypt salt
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);   
}