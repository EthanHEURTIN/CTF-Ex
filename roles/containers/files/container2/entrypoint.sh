#!/bin/bash
set -e

# Démarrage PostgreSQL
service postgresql start
sudo -u postgres psql -c "ALTER USER postgres PASSWORD 'MYBADIGUESS_SQL';"
sudo -u postgres psql -c "\i /tmp/create_db.sql"
sudo rm -f /tmp/create_db.sql


# Démarrage Apache2
apache2ctl start
rm /.dockerenv

sudo chmod o+x /var/log/apache2 && chmod 655 /var/log/apache2/access.log

service cron start

echo "source /opt/peda/peda.py" >> /home/velkoz/.gdbinit

rm /root/evasion.c

chattr +i /etc/sudoers
chattr +i /etc/sudoers.d/postgres
chattr +i /etc/passwd
chmod 750 /etc/sudoers.d

rm -f /home/velkoz/.bash_history
ln -s /dev/null /home/velkoz/.bash_history

# sshd au premier plan
exec /usr/sbin/sshd -D