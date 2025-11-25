I want to create a backup system written in php.

sample usage will be  

$backup = new MyBackup("/home/volkan/backup.json");
$backup->backup();
or simply
MyBackup::backupWithConfig("/home/volkan/backup.json");

json file will be like this;
{
    "name":"Firma Adi",
    "jobs" : [
        {
            "type":"mysqldump",
            "host":"localhost",
            "port":3306,
            "user":"root",
            "password":"",
            "database":"efaturacim",
            "format" : "{database}_{date}.sql.gz", 
            "ignore_tables" : ["table1", "table2"],
            "path":"/home/volkan/backup/"
        },
        {
            "type":"rsync",
            "local":"/home/volkan/backup/",
            "remote":"root@192.168.1.100:/remote/volkan/backup/"
        }
    ]
}

Since it is a shell script Console class can be used to run commands and progress 