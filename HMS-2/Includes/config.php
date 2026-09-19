<?php
if (!defined('DB_SERVER')) {
    define('DB_SERVER', '127.0.0.1:3307');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'hms');
}

if (!isset($con)) {
    /* Attempt to connect to MySQL database */
    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($con === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    mysqli_query($con, "ALTER TABLE member
        ADD COLUMN IF NOT EXISTS current_accommodation VARCHAR(20) NULL,
        ADD COLUMN IF NOT EXISTS tenant_name VARCHAR(100) NULL,
        ADD COLUMN IF NOT EXISTS tenant_contact VARCHAR(30) NULL,
        ADD COLUMN IF NOT EXISTS tenant_start_date DATE NULL,
        ADD COLUMN IF NOT EXISTS tenant_end_date DATE NULL,
        ADD COLUMN IF NOT EXISTS owner_start_date DATE NULL,
        ADD COLUMN IF NOT EXISTS parking_allotted VARCHAR(3) NULL");
}
?>