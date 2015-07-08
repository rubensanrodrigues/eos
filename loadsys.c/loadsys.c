#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

int main(int argc, char *argv[])
{
	if(argc != 8) {
		printf("Argumentos invalidos %d\n", argc);
		exit(1);
	}

	unsigned int m_id = atoi(argv[1]);
	unsigned int S = atoi(argv[2]);
	unsigned int W = atoi(argv[3]);
	unsigned int ts = atoi(argv[4]);
	unsigned int seed = atoi(argv[5]);

	int pos, val;
	char *p;
	char *flog, *fload;
	char jiffies[256];
	long exec_time;
	struct timespec ts_begin, ts_end;
	FILE *fileout, *filejiffies, *dataload;

	sprintf(fload, "data-load-%s-%s-%i.csv", argv[6], argv[7], m_id);
	dataload = fopen(fload, "w+");
	fprintf (dataload, "\"k\";\"v\"\n");

	sprintf(flog, "log-load-%s-%s-%i.csv", argv[6], argv[7], m_id);
	fileout = fopen(flog, "w+");
	fprintf (fileout, "\"jiffies\";\"Latencia\";\"Escrita\";\n");

	p = malloc(S*sizeof(char));

	srand(seed);
	for(;;){
		unsigned int j;
		clock_gettime(CLOCK_MONOTONIC, &ts_begin);
		for(j = 0; j < W; j++) {
			pos = rand() % (S);
			val = rand() % 256;
			p[pos] = val;
			fprintf (dataload, "%i;%i\n", pos, val);
		}
		fflush(dataload);
		clock_gettime(CLOCK_MONOTONIC, &ts_end);
		exec_time = (ts_end.tv_sec - ts_begin.tv_sec) * 1000000000
			+ (ts_end.tv_nsec - ts_begin.tv_nsec);

		filejiffies = fopen("/proc/edosyst/jiffies", "r");
		fgets(jiffies, sizeof(jiffies), filejiffies);
		fclose(filejiffies);
		fprintf (fileout, "%s;%li;%i\n", jiffies, exec_time, W);

		fflush(fileout);
		sleep(ts);
	}
}
