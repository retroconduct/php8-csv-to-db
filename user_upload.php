#!/usr/bin/php
<?php

$shortopts  = "";
$shortopts .= "u:p:h:";

$longopts  = array(
    "file:",
    "create_table",
    "dry_run",
    "help",
);

$options = getopt($shortopts, $longopts);

$csvFile = "";
$tableName = "";
$createTable = false;
$dryRun = false;
$help = false;
$user = "";
$password = "";
$host = "";

if (is_array($options) && count($options) > 0) {
    foreach($options as $optKey => $optValue){
        switch ($optKey) {    
            case "file":
                $csvFile = $optValue;                
                break;
            case "create_table":
                $createTable = ($optValue) ? false : true;
                break;
            case "dry_run":
                $dryRun = ($optValue) ? false : true;
                break;
            case "help":
                $help = ($optValue) ? false : true;
                break;
            case "u":
                $user = $optValue;
                break;
            case "p":
                $password = $optValue;
                break;
            case "h":
                $host = $optValue;
                break;
        }
    }
}