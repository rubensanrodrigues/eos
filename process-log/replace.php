#!/usr/bin/env php
<?php

for ($i=1; $i<=4; $i++) {
	for ($j=1; $j<=15; $j++) {
		if (! is_file("log-stat-$i-$j.csv")) continue;

		$lines = file("log-stat-$i-$j.csv");
		$ho = fopen("log-stat-$i-$j.csv", "w");
		if (!$ho) exit("Não pode apbrir o arquivo csv");
		foreach ($lines as $line) {
			$line = str_replace(",", ";", $line);
			fwrite($ho, $line);
		}
		fclose($ho);
	}
}
