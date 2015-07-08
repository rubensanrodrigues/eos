#!/bin/sh

# /usr/bin/time -f "%E" sleep 2 2> /tmp/teste

SEEDS="./seeds.txt"
EXPS="./exps.txt"

VM="192.168.56.101"
STARTVM="VBoxHeadless --startvm ic "

RBNAME="/root/edosyst/bin"
RUSERNAME="root"

SSHPASS="d8fef51d"

CMD1="cd $RBNAME &&"
CMD3="shutdown -h now"

export DISPLAY=dummydisplay:0
rmpass() {
    rm "$TMPSSHPASS"
}
trap 'rmpass' SIGHUP SIGINT SIGQUIT SIGABRT SIGKILL SIGALRM SIGTERM

i=1
cat $EXPS |
while read EXP
do
	j=1
	for SEED in $(cat $SEEDS)
	do
		#start vm
		echo "." && $STARTVM & > /dev/null

		#wait vm start
		echo "wait vm start"
		while true
		do
			echo "."
			sleep 1
			ping -q -c2 $VM > /dev/null
			if [ $? -eq 0 ]
			then
				break
			else
				continue
			fi
		done

		TMPSSHPASS=$(mktemp)
		chmod 700 "$TMPSSHPASS"
		echo "#!/bin/sh\necho $SSHPASS" > $TMPSSHPASS
		export SSH_ASKPASS=$TMPSSHPASS

		CMD2="java -jar MemoryAnalizer.jar $EXP $SEED $i $j &&"

		#CMD2="touch file-$SEED.csv &&"
		#CMD2="uptime &&"


		#FULLCMD="$CMD1 $CMD2"
		FULLCMD="$CMD1 $CMD2 $CMD3"
		echo $FULLCMD

		#wait vm: sshd up
		sleep 3

		#ssh execute remote command
		setsid ssh -o StrictHostKeyChecking=no -t $RUSERNAME@$VM "$FULLCMD"
		rmpass

		#wait vm stop
		echo "wait vm stop"
		while true
		do
			echo "."
			sleep 2
			ping -q -c2 $VM > /dev/null
			if [ $? -eq 0 ]
			then
				continue
			else
				break
			fi
		done
		j=`expr $j + 1`
	done
	i=`expr $i + 1`
done
