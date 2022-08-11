<?php

// display all errors and warnings on the webpage.
if (DEBUG_FLAG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', '1');
}
