#!/bin/sh

SEEDS="./seeds.txt"
EXPS="./exps.txt"

VM="192.168.0.180"

RBNAME="/home/arq/edosyst-v2/bin"
RUSERNAME="arq"

LYONUSER="memid"
LYONHOST="lyon-01"

SSHPASS="memId123"

CMD1="cd $RBNAME &&"
CMD3="exit"

export DISPLAY=dummydisplay:0
rmpass() {
    rm "$TMPSSHPASS"
}
trap 'rmpass' SIGINT SIGQUIT SIGABRT SIGKILL SIGALRM SIGTERM

#start vm
TMPSSHPASS=$(mktemp)
chmod 700 "$TMPSSHPASS"
echo "#!/bin/sh\necho $SSHPASS" > $TMPSSHPASS
export SSH_ASKPASS=$TMPSSHPASS
setsid ssh -o StrictHostKeyChecking=no -t $LYONUSER@$LYONHOST "virsh start memid"
rmpass

i=1
cat $EXPS |
while read EXP
do
	for SEED in $(cat $SEEDS)
	do
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

		FULLCMD="$CMD1 $CMD2 $CMD3"
		echo $FULLCMD

		#wait vm: sshd up
		sleep 3

		#ssh execute remote command
		setsid ssh -o StrictHostKeyChecking=no -t $RUSERNAME@$VM "$FULLCMD"
		rmpass

		#reboot vm
		TMPSSHPASS=$(mktemp)
		chmod 700 "$TMPSSHPASS"
		echo "#!/bin/sh\necho $SSHPASS" > $TMPSSHPASS
		export SSH_ASKPASS=$TMPSSHPASS
		setsid ssh -o StrictHostKeyChecking=no -t $LYONUSER@$LYONHOST "virsh reboot memid"
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
