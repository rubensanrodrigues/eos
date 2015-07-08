#!/usr/bin/env php
<?php

class tgrp {
	var $jiffies = 0;
	var $group = array();
}

function arithmetic_mean($a) {
	if(!count($a)) return 0;
	return array_sum($a)/count($a);
}

for ($i=1; $i<=32; $i++) {
	for ($j=1; $j<=15; $j++) {
		if (! is_file("log-stat-$i-$j.csv")) continue;

		$times = array();
		$h = fopen("log-stat-$i-$j.csv", "r");
		if (!$h) exit("Não pode apbrir o arquivo csv");
		while (($data = fgetcsv($h, 4096, ';')) !== FALSE) {
			if (!is_numeric($data[1])) continue;
			$tgrp = new tgrp();
			$tgrp->jiffies = (int) $data[1];
			$times[] = $tgrp;
		}
		fclose($h);

		for ($p=0; $p<150; $p++) {
			$f = "log-load-$i-$j-$p.csv";
			if (! is_file($f)) continue;

			$curr = 0; $next = 1;
			$hl = fopen($f, "r");
			if (!$hl) exit("Não pode apbrir o arquivo $f");
			while (($datal = fgetcsv($hl, 4096, ';')) !== FALSE) {
				if (!is_numeric($datal[0])) continue;
				$jiffies = (int) $datal[0];
				$tdiff = (int) $datal[1];

				if (isset($times[$next])) {
					while (($jiffies > $times[$curr]->jiffies) && ($jiffies > $times[$next]->jiffies)) {
						if (!isset($times[$next+1])) break;

						$curr++;
						$next++;
					}

					if ( ($jiffies >= $times[$curr]->jiffies) && ($jiffies < $times[$next]->jiffies)) {
						$times[$curr]->group[] = $tdiff;
					}

					if ($jiffies >= $times[$next]->jiffies) {
						$times[$next]->group[] = $tdiff;
						$curr++;
						$next++;
					}

				} else {
					$times[$curr]->group[] = $tdiff;
				}
			}
			fclose($hl);
		}

		$h = fopen("log-stat-$i-$j.csv", "r");
		if (!$h) exit("Não pode apbrir o arquivo csv");

		$ho = fopen("log-spr-$i-$j.csv", "w");
		if (!$ho) exit("Não pode apbrir o arquivo csv");
		$x=0;
		while (($data = fgetcsv($h, 4096, ';')) !== FALSE) {
			if (!isset($data[7])) echo "$x log-stat-$i-$j.csv\n";
			$str = "";
			if (!is_numeric($data[1])) {
				$str = "{$data[0]};{$data[1]};{$data[2]};{$data[3]};{$data[4]};"
					."{$data[5]};{$data[6]};{$data[7]};Latencia;\n";
			} else {
				$t = $times[$x];
				$x++;

				if (((int) $data[1]) != $t->jiffies) exit("Erro de sicronia do arquivos csv");
				$latencia = floor(arithmetic_mean($t->group));
				$str = "{$data[0]};{$data[1]};{$data[2]};{$data[3]};{$data[4]};"
					."{$data[5]};{$data[6]};{$data[7]};$latencia;\n";
			}

			fwrite($ho, $str);
		}

		fclose($h);
		fclose($ho);
	}
	file_put_contents("log-rscript-$i.r", rcomands($i));
	exec("/usr/bin/Rscript log-rscript-$i.r");
}


function rcomands($id) {
return <<<EOD
#!/usr/bin/Rscript
log.spr.1.1 <- read.csv("log-spr-$id-1.csv", sep=";")
log.spr.1.2 <- read.csv("log-spr-$id-2.csv", sep=";")
log.spr.1.3 <- read.csv("log-spr-$id-3.csv", sep=";")
log.spr.1.4 <- read.csv("log-spr-$id-4.csv", sep=";")
log.spr.1.5 <- read.csv("log-spr-$id-5.csv", sep=";")
log.spr.1.6 <- read.csv("log-spr-$id-6.csv", sep=";")
log.spr.1.7 <- read.csv("log-spr-$id-7.csv", sep=";")
log.spr.1.8 <- read.csv("log-spr-$id-8.csv", sep=";")
log.spr.1.9 <- read.csv("log-spr-$id-9.csv", sep=";")
log.spr.1.10 <- read.csv("log-spr-$id-10.csv", sep=";")
log.spr.1.11 <- read.csv("log-spr-$id-11.csv", sep=";")
log.spr.1.12 <- read.csv("log-spr-$id-12.csv", sep=";")
log.spr.1.13 <- read.csv("log-spr-$id-13.csv", sep=";")
log.spr.1.14 <- read.csv("log-spr-$id-14.csv", sep=";")
log.spr.1.15 <- read.csv("log-spr-$id-15.csv", sep=";")

len = length(log.spr.1.1\$Time)
if (length(log.spr.1.2\$Time) < len) len=length(log.spr.1.2\$Time)
if (length(log.spr.1.3\$Time) < len) len=length(log.spr.1.3\$Time)
if (length(log.spr.1.4\$Time) < len) len=length(log.spr.1.4\$Time)
if (length(log.spr.1.5\$Time) < len) len=length(log.spr.1.5\$Time)
if (length(log.spr.1.6\$Time) < len) len=length(log.spr.1.6\$Time)
if (length(log.spr.1.7\$Time) < len) len=length(log.spr.1.7\$Time)
if (length(log.spr.1.8\$Time) < len) len=length(log.spr.1.8\$Time)
if (length(log.spr.1.9\$Time) < len) len=length(log.spr.1.9\$Time)
if (length(log.spr.1.10\$Time) < len) len=length(log.spr.1.10\$Time)
if (length(log.spr.1.11\$Time) < len) len=length(log.spr.1.11\$Time)
if (length(log.spr.1.12\$Time) < len) len=length(log.spr.1.12\$Time)
if (length(log.spr.1.13\$Time) < len) len=length(log.spr.1.13\$Time)
if (length(log.spr.1.14\$Time) < len) len=length(log.spr.1.14\$Time)
if (length(log.spr.1.15\$Time) < len) len=length(log.spr.1.15\$Time)

lmin=len-1

um = rowMeans(cbind(
		(log.spr.1.1\$CPU.user[2:len] - log.spr.1.1\$CPU.user[1:lmin]),
		(log.spr.1.2\$CPU.user[2:len] - log.spr.1.2\$CPU.user[1:lmin]),
		(log.spr.1.3\$CPU.user[2:len] - log.spr.1.3\$CPU.user[1:lmin]),
		(log.spr.1.4\$CPU.user[2:len] - log.spr.1.4\$CPU.user[1:lmin]),
		(log.spr.1.5\$CPU.user[2:len] - log.spr.1.5\$CPU.user[1:lmin]),
		(log.spr.1.6\$CPU.user[2:len] - log.spr.1.6\$CPU.user[1:lmin]),
		(log.spr.1.7\$CPU.user[2:len] - log.spr.1.7\$CPU.user[1:lmin]),
		(log.spr.1.8\$CPU.user[2:len] - log.spr.1.8\$CPU.user[1:lmin]),
		(log.spr.1.9\$CPU.user[2:len] - log.spr.1.9\$CPU.user[1:lmin]),
		(log.spr.1.10\$CPU.user[2:len] - log.spr.1.10\$CPU.user[1:lmin]),
		(log.spr.1.11\$CPU.user[2:len] - log.spr.1.11\$CPU.user[1:lmin]),
		(log.spr.1.12\$CPU.user[2:len] - log.spr.1.12\$CPU.user[1:lmin]),
		(log.spr.1.13\$CPU.user[2:len] - log.spr.1.13\$CPU.user[1:lmin]),
		(log.spr.1.14\$CPU.user[2:len] - log.spr.1.14\$CPU.user[1:lmin]),
		(log.spr.1.15\$CPU.user[2:len] - log.spr.1.15\$CPU.user[1:lmin])
))

km = rowMeans(cbind(
		(log.spr.1.1\$CPU.kernel[2:len] - log.spr.1.1\$CPU.kernel[1:lmin]),
		(log.spr.1.2\$CPU.kernel[2:len] - log.spr.1.2\$CPU.kernel[1:lmin]),
		(log.spr.1.3\$CPU.kernel[2:len] - log.spr.1.3\$CPU.kernel[1:lmin]),
		(log.spr.1.4\$CPU.kernel[2:len] - log.spr.1.4\$CPU.kernel[1:lmin]),
		(log.spr.1.5\$CPU.kernel[2:len] - log.spr.1.5\$CPU.kernel[1:lmin]),
		(log.spr.1.6\$CPU.kernel[2:len] - log.spr.1.6\$CPU.kernel[1:lmin]),
		(log.spr.1.7\$CPU.kernel[2:len] - log.spr.1.7\$CPU.kernel[1:lmin]),
		(log.spr.1.8\$CPU.kernel[2:len] - log.spr.1.8\$CPU.kernel[1:lmin]),
		(log.spr.1.9\$CPU.kernel[2:len] - log.spr.1.9\$CPU.kernel[1:lmin]),
		(log.spr.1.10\$CPU.kernel[2:len] - log.spr.1.10\$CPU.kernel[1:lmin]),
		(log.spr.1.11\$CPU.kernel[2:len] - log.spr.1.11\$CPU.kernel[1:lmin]),
		(log.spr.1.12\$CPU.kernel[2:len] - log.spr.1.12\$CPU.kernel[1:lmin]),
		(log.spr.1.13\$CPU.user[2:len] - log.spr.1.13\$CPU.user[1:lmin]),
		(log.spr.1.14\$CPU.user[2:len] - log.spr.1.14\$CPU.user[1:lmin]),
		(log.spr.1.15\$CPU.user[2:len] - log.spr.1.15\$CPU.user[1:lmin])
))


ctm = rowMeans(cbind(
		(log.spr.1.1\$CPU.total[2:len] - log.spr.1.1\$CPU.total[1:lmin]),
		(log.spr.1.2\$CPU.total[2:len] - log.spr.1.2\$CPU.total[1:lmin]),
		(log.spr.1.3\$CPU.total[2:len] - log.spr.1.3\$CPU.total[1:lmin]),
		(log.spr.1.4\$CPU.total[2:len] - log.spr.1.4\$CPU.total[1:lmin]),
		(log.spr.1.5\$CPU.total[2:len] - log.spr.1.5\$CPU.total[1:lmin]),
		(log.spr.1.6\$CPU.total[2:len] - log.spr.1.6\$CPU.total[1:lmin]),
		(log.spr.1.7\$CPU.total[2:len] - log.spr.1.7\$CPU.total[1:lmin]),
		(log.spr.1.8\$CPU.total[2:len] - log.spr.1.8\$CPU.total[1:lmin]),
		(log.spr.1.9\$CPU.total[2:len] - log.spr.1.9\$CPU.total[1:lmin]),
		(log.spr.1.10\$CPU.total[2:len] - log.spr.1.10\$CPU.total[1:lmin]),
		(log.spr.1.11\$CPU.total[2:len] - log.spr.1.11\$CPU.total[1:lmin]),
		(log.spr.1.12\$CPU.total[2:len] - log.spr.1.12\$CPU.total[1:lmin]),
		(log.spr.1.13\$CPU.user[2:len] - log.spr.1.13\$CPU.user[1:lmin]),
		(log.spr.1.14\$CPU.user[2:len] - log.spr.1.14\$CPU.user[1:lmin]),
		(log.spr.1.15\$CPU.user[2:len] - log.spr.1.15\$CPU.user[1:lmin])
))


pim = rowMeans(cbind(
		(log.spr.1.1\$Page.in[2:len] - log.spr.1.1\$Page.in[1:lmin]),
		(log.spr.1.2\$Page.in[2:len] - log.spr.1.2\$Page.in[1:lmin]),
		(log.spr.1.3\$Page.in[2:len] - log.spr.1.3\$Page.in[1:lmin]),
		(log.spr.1.4\$Page.in[2:len] - log.spr.1.4\$Page.in[1:lmin]),
		(log.spr.1.5\$Page.in[2:len] - log.spr.1.5\$Page.in[1:lmin]),
		(log.spr.1.6\$Page.in[2:len] - log.spr.1.6\$Page.in[1:lmin]),
		(log.spr.1.7\$Page.in[2:len] - log.spr.1.7\$Page.in[1:lmin]),
		(log.spr.1.8\$Page.in[2:len] - log.spr.1.8\$Page.in[1:lmin]),
		(log.spr.1.9\$Page.in[2:len] - log.spr.1.9\$Page.in[1:lmin]),
		(log.spr.1.10\$Page.in[2:len] - log.spr.1.10\$Page.in[1:lmin]),
		(log.spr.1.11\$Page.in[2:len] - log.spr.1.11\$Page.in[1:lmin]),
		(log.spr.1.12\$Page.in[2:len] - log.spr.1.12\$Page.in[1:lmin]),
		(log.spr.1.13\$CPU.user[2:len] - log.spr.1.13\$CPU.user[1:lmin]),
		(log.spr.1.14\$CPU.user[2:len] - log.spr.1.14\$CPU.user[1:lmin]),
		(log.spr.1.15\$CPU.user[2:len] - log.spr.1.15\$CPU.user[1:lmin])
))

pom = rowMeans(cbind(
		(log.spr.1.1\$Page.out[2:len] - log.spr.1.1\$Page.out[1:lmin]),
		(log.spr.1.2\$Page.out[2:len] - log.spr.1.2\$Page.out[1:lmin]),
		(log.spr.1.3\$Page.out[2:len] - log.spr.1.3\$Page.out[1:lmin]),
		(log.spr.1.4\$Page.out[2:len] - log.spr.1.4\$Page.out[1:lmin]),
		(log.spr.1.5\$Page.out[2:len] - log.spr.1.5\$Page.out[1:lmin]),
		(log.spr.1.6\$Page.out[2:len] - log.spr.1.6\$Page.out[1:lmin]),
		(log.spr.1.7\$Page.out[2:len] - log.spr.1.7\$Page.out[1:lmin]),
		(log.spr.1.8\$Page.out[2:len] - log.spr.1.8\$Page.out[1:lmin]),
		(log.spr.1.9\$Page.out[2:len] - log.spr.1.9\$Page.out[1:lmin]),
		(log.spr.1.10\$Page.out[2:len] - log.spr.1.10\$Page.out[1:lmin]),
		(log.spr.1.11\$Page.out[2:len] - log.spr.1.11\$Page.out[1:lmin]),
		(log.spr.1.12\$Page.out[2:len] - log.spr.1.12\$Page.out[1:lmin]),
		(log.spr.1.13\$CPU.user[2:len] - log.spr.1.13\$CPU.user[1:lmin]),
		(log.spr.1.14\$CPU.user[2:len] - log.spr.1.14\$CPU.user[1:lmin]),
		(log.spr.1.15\$CPU.user[2:len] - log.spr.1.15\$CPU.user[1:lmin])
))



mum = rowMeans(cbind(
		(log.spr.1.1\$MEM.used[2:len]),
		(log.spr.1.2\$MEM.used[2:len]),
		(log.spr.1.3\$MEM.used[2:len]),
		(log.spr.1.4\$MEM.used[2:len]),
		(log.spr.1.5\$MEM.used[2:len]),
		(log.spr.1.6\$MEM.used[2:len]),
		(log.spr.1.7\$MEM.used[2:len]),
		(log.spr.1.8\$MEM.used[2:len]),
		(log.spr.1.9\$MEM.used[2:len]),
		(log.spr.1.10\$MEM.used[2:len]),
		(log.spr.1.11\$MEM.used[2:len]),
		(log.spr.1.12\$MEM.used[2:len])
))

lam = rowMeans(cbind(
		(log.spr.1.1\$Latencia[2:len]),
		(log.spr.1.2\$Latencia[2:len]),
		(log.spr.1.3\$Latencia[2:len]),
		(log.spr.1.4\$Latencia[2:len]),
		(log.spr.1.5\$Latencia[2:len]),
		(log.spr.1.6\$Latencia[2:len]),
		(log.spr.1.7\$Latencia[2:len]),
		(log.spr.1.8\$Latencia[2:len]),
		(log.spr.1.9\$Latencia[2:len]),
		(log.spr.1.10\$Latencia[2:len]),
		(log.spr.1.11\$Latencia[2:len]),
		(log.spr.1.12\$Latencia[2:len])
))

setEPS()
postscript("../grafico-$id-cpupg.eps")
par(mar=c(5, 4, 4, 6) + 0.1)
	plot((um+km)/100, type="l", col="blue", main="CPU vs Paginação", xlab="", ylab="", axes=FALSE, ylim=c(0,1), pch=16)
	axis(2, ylim=c(0,1),col="black",las=1)
	mtext("CPU",side=2,line=2.5)
par(new=TRUE)
	plot(pim, type="l", col="black", xlab="", ylab="", axes=FALSE, ylim=c(0,round(max(pim,pom))), pch=15)
	lines(pom, type="l", col="red")
	axis(4, ylim=c(0,round(max(pim,pom))), col="black",col.axis="black",las=1)
	mtext("# páginas",side=4,col="black",line=4)
axis(1,pretty(range(1:length(pim)),10))
mtext("Tempo (segundos)",side=1,col="black",line=2.5)
legend("topright",legend=c("CPU","Page-in","Page-out"), text.col=c("blue","black","red"),pch=c(16,15),col=c("blue","black","red"))
grid()
dev.off()


setEPS()
postscript("../grafico-$id-mempg.eps")
par(mar=c(5, 4, 4, 6) + 0.1)
	plot(mum/1024, type="l", col="blue", main="Memória (principal + swap) vs Paginação", xlab="", ylab="", axes=FALSE, ylim=c(0,round(max(mum)/1024)), pch=16)
	axis(2, ylim=c(0,round(max(mum)/1024)), col="black",las=1)
	mtext("Memória (MB)",side=2,line=2.5)
par(new=TRUE)
	plot(pim, type="l", col="black", xlab="", ylab="", axes=FALSE, ylim=c(0,round(max(pim,pom))), pch=15)
	lines(pom, type="l", col="red")
	axis(4, ylim=c(0,round(max(pim,pom))), col="black",col.axis="black",las=1)
	mtext("# páginas",side=4,col="black",line=4)
axis(1,pretty(range(1:length(pim)),10))
mtext("Tempo (segundos)",side=1,col="black",line=2.5)
legend("topright",legend=c("Memória","Page-in","Page-out"), text.col=c("blue","black","red"),pch=c(16,15),col=c("blue","black","red"))
grid()
dev.off()

setEPS()
postscript("../grafico-$id-cpult.eps")
par(mar=c(5, 4, 4, 6) + 0.1)
	plot((um+km)/ctm, type="l", col="blue", main="CPU vs Latência", xlab="", ylab="", axes=FALSE, ylim=c(0,1), pch=16)
	axis(2, ylim=c(0,1),col="black",las=1)
	mtext("CPU",side=2,line=2.5)
par(new=TRUE)
	plot(lam/(1000000), type="l", col="black", xlab="", ylab="", axes=FALSE, ylim=c(0,round(max(lam/(1000000)))), pch=15)
	axis(4, ylim=c(0,round(max(lam/(1000000)))), col="black",col.axis="black",las=1)
	mtext("Latência: Nano Sec./1.000.000",side=4,col="black",line=4)
axis(1,pretty(range(1:length(lam/(1000000))),10))
mtext("Tempo (segundos)",side=1,col="black",line=2.5)
legend("topright",legend=c("CPU","Latência"), text.col=c("blue","black"), pch=c(16,15),col=c("blue","black"))
grid()
dev.off()
EOD;

}
