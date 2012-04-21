<?php
/*
 * Läd das Framework
 * 
 * (A) Mario XEdP3X
 * (c) GPL
 */

if ($handle = scandir('config/')) {
    while( list ( $key, $val ) = each ( $handle ) ){
        if (($val[0] != ".") AND (substr($val,-4)==".php")) {
        	include_once ("config/$val");
        }
    }
}

define("lib",true);

if ($handle = scandir('lib/')) {
    while( list ( $key, $val ) = each ( $handle ) ){
        if ($val[0] != ".") {
        	include_once ("lib/$val");
        }
    }
}