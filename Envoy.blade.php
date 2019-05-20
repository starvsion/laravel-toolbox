@servers(['localhost' => '127.0.0.1'])

@task('setup-local',  ['on' => 'localhost', 'confirm' => true])
    sudo dnf update
    echo "\nInstalling PHP...\n"
    sudo dnf install composer php-mysqlnd php- nss-tools jq xsel
    echo "\nInstalling MariaDB and set root password to root...\n"
    sudo dnf install mariadb mariadb-server mycli
    sudo systemctl enable mariadb --now
    #http://bertvv.github.io/notes-to-self/2015/11/16/automating-mysql_secure_installation/
    myql --user=root <<_EOF_
UPDATE mysql.user SET Password=PASSWORD('root') WHERE User='root';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
_EOF_
    echo  "\nInstalling Redis...\n"
    sudo dnf install redis
    sudo systemctl enable redis --now
    echo  "\nInstalling Redis...\n"
@endtask
