<?php

require 'database\Database.php';

$config = require('database\config_db.php');
$db = new Database($config['database']);

if($db)
    echo "bravo";

?>