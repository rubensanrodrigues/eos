#include <stdio.h>
#include <string.h>
#include <stdlib.h>

struct mystats {
	char cpusystem[20];
	char cpuuser[20];
	char pgpgin[20];
	char pgpgout[20];
};

char ** explode (char sep, char *str, int *pcount);
char *trim (char *s);

int main(int argc, char *argv[]) {

	if (argc != 2) {
		fprintf (stderr, "argumento invalido para o nome do arquivos!\n");
		return 1;
	}

    FILE *file, *fileout;
    char line[256];
    char *pos;
    char **toks;
	int i = 0, j = 0;
	int count = 0;

	struct mystats stats;

	fileout = fopen(argv[1], "w+");

	for (;;) {
		file = fopen("/proc/stat", "r");
		fgets(line, sizeof(line), file);

		if (strlen(line) > 0) {
			toks = explode(' ', line, &count);
			for (i=0; i < (count+1); i++) {
				if (i == 2 || i == 4) {
					if (i == 2)
						strcpy(stats.cpusystem, trim(toks[i]));
					else
						strcpy(stats.cpuuser, trim(toks[i]));
				}
			}
		}

		free(toks);
		fclose(file);

		j = 0;
		file = fopen("/proc/vmstat", "r");
		while(fgets(line, sizeof(line), file) != NULL) {
			if(j == 29 || j == 30) {
				toks = explode(' ', line, &count);
				for (i=0; i < (count+1); i++) {
					if(i == 1) {
						if (j == 29)
							strcpy(stats.pgpgin, trim(toks[i]));
						else
							strcpy(stats.pgpgout, trim(toks[i]));
					}
				}
			}

			j++;
		}

		free(toks);
		fclose(file);
		fprintf (fileout, "%s;%s;%s;%s\n", stats.cpusystem,
				stats.cpuuser, stats.pgpgin, stats.pgpgout);
		fflush(fileout);

		sleep(1);
    }
}

char ** explode (char sep, char *str, int *pcount)
{
 	char **arr_str = (char**) malloc(0);
 	int count = 0;
 	char *cp = str;
 	char *apos = str;

 	while ((cp = strchr(cp, sep)) != NULL) {
		count++;

		arr_str = (char **)realloc(arr_str, count*sizeof(char*));
		arr_str[count-1] = (char *)realloc(arr_str[count-1], cp - apos);
		strncpy(arr_str[count-1], apos, cp - apos);

		cp +=sizeof(char);
		apos = cp;
 	}

 	arr_str = (char **)realloc(arr_str, (count+1)*sizeof(char*));
 	arr_str[count] = (char *)realloc(arr_str[count], &str[strlen(str)] - apos);
 	strncpy(arr_str[count], apos, &str[strlen(str)] - apos);

	*pcount = count;
 	return arr_str;
}

char *trim (char *s)
{
	int i = 0;
	int j = strlen ( s ) - 1;
	int k = 0;
	while ( isspace ( s[i] ) && s[i] != '\0' )
		i++;

	while ( isspace ( s[j] ) && j >= 0 )
		j--;

	while ( i <= j )
		s[k++] = s[i++];

	s[k] = '\0';

	return s;
}
