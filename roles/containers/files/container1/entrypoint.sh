#!/bin/bash
set -e

# vsftpd en background
/usr/sbin/vsftpd /etc/vsftpd.conf &

apache2ctl start

rm /.dockerenv

service cron start

echo '127.0.0.1 prankex.io' | tee -a /etc/hosts

chattr +i /opt/dingz/roulette.py

chattr +i /etc/sudoers
chattr +i /etc/sudoers.d/dinohh
chattr +i /etc/sudoers.d/mbappinho
chattr +i /etc/passwd

rm -f /home/mbappinho/.bash_history
ln -s /dev/null /home/mbappinho/.bash_history

# sshd au premier plan
exec /usr/sbin/sshd -D