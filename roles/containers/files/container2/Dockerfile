FROM debian:12
ENV DEBIAN_FRONTEND=noninteractive

RUN echo 'deb http://deb.debian.org/debian bullseye main\ndeb http://deb.debian.org/debian bullseye-updates main\ndeb http://security.debian.org bullseye-security main' > /etc/apt/sources.list.d/bullseye.list 

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    openssh-server iptables \
    iproute2 iputils-ping net-tools dnsutils curl ca-certificates vim nano cron wget \
    postgresql postgresql-client sudo apache2 php libapache2-mod-php gcc gdb build-essential php-pgsql netcat-traditional git \
    python2.7 file \
 && rm -rf /var/lib/apt/lists/* \
 && mkdir -p /run/sshd

RUN rm /.dockerenv

###########################
## Configuration SSH
###########################

RUN sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config \
 && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config \
 && sed -i 's/#PubkeyAuthentication yes/PubkeyAuthentication yes/' /etc/ssh/sshd_config

RUN useradd -s /bin/bash -m velkoz \
 && echo "velkoz:Rh0b4l@s_l5_C4NC3II3D_i" | chpasswd \
 && echo "root:Dedier-Haler-Oeil-Scandale2" | chpasswd \ 

EXPOSE 22

###########################
## Configuration APACHE
###########################

RUN a2enmod rewrite
COPY apache-config.conf /etc/apache2/sites-available/my-site.conf
RUN a2dissite 000-default && a2ensite my-site

COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

RUN mkdir -p /home/velkoz/.ssh && mkdir -p /home/velkoz/.hide && chmod 700 /home/velkoz/.ssh && chmod 700 /home/velkoz/.hide
RUN chmod 700 /home/velkoz
RUN touch /home/velkoz/.ssh/authorized_keys
RUN chown -R velkoz:velkoz /home/velkoz/

COPY create_db.sql /tmp/create_db.sql

COPY root.crontab /tmp/root.crontab
RUN crontab /tmp/root.crontab && rm /tmp/root.crontab

EXPOSE 80

###########################
## Lateral Escalation
###########################

RUN echo "postgres ALL=(velkoz) NOPASSWD: /usr/bin/tee" > /etc/sudoers.d/postgres

###########################
## Privilege Escalation
###########################

COPY evasion.c /root/evasion.c
RUN gcc -fno-stack-protector -z execstack /root/evasion.c -o /root/evasion

RUN git clone https://github.com/longld/peda.git /opt/peda
RUN mv /root/evasion /home/velkoz/.hide/evasion
RUN chmod +s /home/velkoz/.hide/evasion

COPY cosmo_note.txt /root/note.txt

###########################
## FLAGS
###########################

COPY flags/flag5.txt /var/www/html/flag.txt
RUN chmod 700 /var/lib/postgresql
COPY flags/flag6.txt /var/lib/postgresql/15/main/flag.txt
COPY flags/flag7.txt /home/velkoz/flag.txt
COPY flags/flag8.txt /root/flag.txt

###########################
## SCRIPT LANCEMENT
###########################

COPY entrypoint.sh /root/.entrypoint.sh
RUN chmod +x /root/.entrypoint.sh
CMD ["/root/.entrypoint.sh"]
