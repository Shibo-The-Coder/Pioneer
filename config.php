<?php
    define("DB_NAME", "mysql:dbname=pioneer3;host=localhost;charset=utf8;" );
    define( "DB_USERNAME", "root" );
    define( "DB_PASSWORD", "" );
    define("BASENAME", "/pioneer/");
    define("BASEDIR", $_SERVER['DOCUMENT_ROOT'].BASENAME);
    define( "CLASSES_PATH", BASEDIR."classes/" );
    define( "SCRIPTS_PATH", BASEDIR."scripts/" );
    define( "UPLOAD_PATH", BASEDIR."uploads/" );
    define("SITEURL", "http://".$_SERVER['SERVER_NAME'].BASENAME);
    define("PERMALINKS", true); //Beautify our urls :)
    define("DEFAULT_LIMIT", 25);
    define("DEFAULT_OFFSET", 0);
?>
