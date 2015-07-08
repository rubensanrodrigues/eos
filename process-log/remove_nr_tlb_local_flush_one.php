#!/usr/bin/env php
<?php
for ($i=1; $i<=32; $i++) {
	for ($j=1; $j<=15; $j++) {
		if (! is_file("log-stat-$i-$j.csv")) continue;

		$h = fopen("log-stat-$i-$j.csv", "r");
		if (!$h) exit("Não pode apbrir o arquivo csv");

		$ho = fopen("log-stat-$i-$j.csv.tmp", "w");
		if (!$ho) exit("Não pode apbrir o arquivo csv");

		$abuffer = "";
		$data = array();
		while (($buffer = fgets($h, 4096)) !== false) {
			if (strpos($buffer, 'nr_tlb_local_flush_one') !== false) {
				$data = explode(";", $abuffer);
				$data[0] = 1 + ((int) $data[0]);
				$data[7] = trim($data[7]);
				$buffer = "{$data[0]};{$data[1]};{$data[2]};{$data[3]};"
					."{$data[4]};{$data[5]};{$data[6]};{$data[7]}\n";

				echo "$buffer\n$abuffer\n";
				$abuffer = $buffer;


				fgets($h, 4096);
			}
			else {
				$abuffer = $buffer;
			}
			fwrite($ho, $buffer);
		}

		fclose($h);
		fclose($ho);

		unlink("log-stat-$i-$j.csv");
		rename("log-stat-$i-$j.csv.tmp","log-stat-$i-$j.csv");
	}
}
