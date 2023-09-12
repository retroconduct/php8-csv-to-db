#!/usr/bin/php
<?php

include('ProcessFile.php');

$shortopts  = "";
$shortopts .= "u:p:h:";

$longopts  = array(
    "file:",
    "create_table",
    "delete_table",
    "dry_run",
    "help",
);

$options = getopt($shortopts, $longopts);

$file = "";
$tableName = "";
$createTable = false;
$deleteTable = false;
$dryRun = false;
$help = false;
$database = "user";
$user = "";
$password = "";
$host = "";

if (is_array($options) && count($options) > 0) {
    foreach($options as $optKey => $optValue){
        switch ($optKey) {    
            case "file":
                $file = $optValue;                
                break;
            case "create_table":
                $createTable = ($optValue) ? false : true;
                break;
            case "delete_table":
                $deleteTable = ($optValue) ? false : true;
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

try {
    $pf = new ProcessFile($user, $password, $host, $file);

    if ($help) {

    } else {
        if ($createTable) {
            try {
                print "Performing : Create Table \n";
                $pf->createTable();
                print "Complete : Create Table \n";
            } catch (Exception $e) {
                print "\n";
                print "Error Creating Table: " . $e->getMessage();
                print "\n";
            }
        } elseif ($deleteTable) {
            try {
                print "Performing : Delete Table \n";
                $pf->deleteTable();
                print "Complete : Delete Table \n";
            } catch (Exception $e) {
                print "\n";
                print "Error Deleting Table: " . $e->getMessage();
                print "\n";
            }
        } elseif ($dryRun) {
            try {
                print "Performing : Dry Run \n";
                $pf->run($dry = true);
                print "Complete : Dry Run \n";
            } catch (Exception $e) {
                print "\n";
                print "Error Creating Dry Run: " . $e->getMessage();
                print "\n";
            }
        } else {
            try {
                print "Performing : Run \n";
                $pf->run($dry = false);
                print "Complete : Run \n";
            } catch (Exception $e) {
                print "\n";
                print "Error Processing File: " . $e->getMessage();
                print "\n";
            }
        }
    }
} catch (Exception $e) {
    print "\n";
    print "Error Spinning up Application: " . $e->getMessage();
    print "\n";
}