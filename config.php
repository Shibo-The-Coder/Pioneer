<?php
    define("DB_NAME", "mysql:dbname=pioneer3;host=localhost;charset=utf8;" );
    define( "DB_USERNAME", "root" );
    define( "DB_PASSWORD", "" );
    define( "CLASSES_PATH", "./classes/" );
    define( "SCRIPTS_PATH", "./scripts/" );
    define("BASENAME", "/pioneer/");
    define("BASEDIR", "http://".$_SERVER['SERVER_NAME'].BASENAME);
    define("PERMALINKS", true); //Beautify our urls :)
    define("DEFAULT_LIMIT", 25);
    define("DEFAULT_OFFSET", 0);
?>
