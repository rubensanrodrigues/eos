obj-m := loadsysmod.o
KDIR := /lib/modules/$(shell uname -r)/build
PWD := $(shell pwd)

all:
	$(MAKE) -C $(KDIR) M=$(PWD) modules

clean:
	$(MAKE) -C $(KDIR) M=$(PWD) clean

install:
	mkdir /lib/modules/`uname -r`/kernel/drivers/edosyst/
	cp loadsysmod.ko /lib/modules/`uname -r`/kernel/drivers/edosyst/loadsysmod.ko
	chmod 755 /lib/modules/`uname -r`/kernel/drivers/edosyst/loadsysmod.ko
	depmod -a
	echo "modprobe loadsysmod" >> /etc/rc.local
	modprobe loadsysmod

uninstall:
	rm /lib/modules/`uname -r`/kernel/drivers/edosyst/loadsysmod.ko
	rm -r /lib/modules/`uname -r`/kernel/drivers/edosyst/
	modprobe -r loadsysmod
	sed -i '/modprobe loadsysmod/d' /etc/rc.local
