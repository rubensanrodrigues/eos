#include <linux/module.h>
#include <linux/kernel.h>
#include <linux/init.h>
#include <linux/proc_fs.h>
#include <linux/jiffies.h>
#include <asm/uaccess.h>

static struct proc_dir_entry *edosyst_dir, *jiffies_file;


static int write_first(struct file *file,
                             const char __user *buffer,
                             unsigned long count,
                             void *data)
{
	if (count < 0 || count > 1024)
		return -EFAULT;

	return 0;
}

static int read_first(char *page, char **start,
                            off_t off, int count,
                            int *eof, void *data)
{
	int offset = 0;
	char message[256];
	sprintf(message, "%li", jiffies);
	strcpy(page + offset, message);
	offset += strlen(message);
	memcpy(page + offset, data, strlen(data));
	offset += strlen(data);
	return offset;
}

static int __init loadsys_module_init(void)
{
	/* create a directory */
	edosyst_dir = proc_mkdir("edosyst", NULL);
        if(edosyst_dir == NULL)
                return -ENOMEM;

        /* create a file */
        jiffies_file = create_proc_entry("jiffies", 0644, edosyst_dir);
        if(jiffies_file == NULL) {
		remove_proc_entry("edosyst", NULL);
		return -ENOMEM;
	}
	jiffies_file->data = kmalloc(256, GFP_KERNEL);
	strcpy(jiffies_file->data, "0");
	jiffies_file->read_proc = read_first;
	jiffies_file->write_proc = write_first;
    return 0;
}

static void __exit loadsys_module_exit(void)
{
	kfree(jiffies_file->data);
	remove_proc_entry("jiffies", edosyst_dir);
	remove_proc_entry("edosyst", NULL);
}

module_init(loadsys_module_init);
module_exit(loadsys_module_exit);
MODULE_LICENSE("GPL");
