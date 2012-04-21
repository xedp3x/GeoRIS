<?php
include_once 'config/setting.php';

define("lib",true);


if ($handle = scandir('lib/')) {
    while( list ( $key, $val ) = each ( $handle ) ){
        if ($val[0] != ".") {
        	include_once ("lib/$val");
        }
    }
}