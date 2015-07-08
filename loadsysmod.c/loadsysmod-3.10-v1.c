#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/proc_fs.h>
#include <linux/sched.h>
#include <asm/uaccess.h>

static int read_proc(struct file *filp, char *buf, size_t count, loff_t *offp )
{
	char message[256];

	sprintf(message, "%li", jiffies);
	copy_to_user(buf, message, count);

	return count;
}

static struct file_operations proc_fops = { read:read_proc };

static void create_new_proc_entry()
{
	proc_create("jiffies", 0, NULL, &proc_fops);
}


static int proc_init (void)
{
	create_new_proc_entry();
	return 0;
}

static void proc_cleanup(void)
{
	remove_proc_entry("jiffies",NULL);
}

MODULE_LICENSE("GPL");
module_init(proc_init);
module_exit(proc_cleanup);
