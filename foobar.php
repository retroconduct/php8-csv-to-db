#!/usr/bin/php
<?php

for ($i = 1; $i <= 100; $i++) {
	if ($i%5 == 0 && $i%3 == 0) {
		print "foobar,";
	} elseif ($i%5 == 0) {
		print "bar,";
	} elseif ($i%3 == 0) {
		print "foo,";
	} else {
		print $i . ",";
	}
}