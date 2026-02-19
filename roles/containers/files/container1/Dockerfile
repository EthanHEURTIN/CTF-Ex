FROM debian:12
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    openssh-server iptables \
    iproute2 iputils-ping net-tools dnsutils curl ca-certificates python3 python3-pip python3-requests vsftpd \
    apache2 php libapache2-mod-php php-sqlite3 vim nano cron gcc make build-essential libpam0g-dev \
    wget gnupg unzip netcat-traditional man nmap \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* \
 && mkdir -p /run/sshd

RUN rm /.dockerenv

###########################
## Installation SUDO
###########################

WORKDIR /tmp
RUN wget https://www.sudo.ws/dist/sudo-1.8.27.tar.gz && \
    tar -xzf sudo-1.8.27.tar.gz

WORKDIR /tmp/sudo-1.8.27
RUN ./configure --prefix=/usr \
                --with-pam \
                --with-logging=syslog \
                --with-logfac=authpriv && \
    make && \
    make install

RUN rm -rf /tmp/sudo-1.8.27*
WORKDIR /

#####################################
## Installation Chrome & Selenium
#####################################

# Ajout du repo Google Chrome et installation
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update \
    && apt-get install -y google-chrome-stable \
    && rm -rf /var/lib/apt/lists/*

RUN pip install selenium --break-system-packages

###########################
## Configuration FTP
###########################

RUN mkdir -p /var/run/vsftpd/empty \
    && mkdir -p /srv/ftp \
    && chown root:root /srv/ftp \
    && chmod 755 /srv/ftp
COPY vsftpd.conf /etc/vsftpd.conf
COPY noteFTP.txt /srv/ftp/.note.txt
RUN chmod 644 /srv/ftp/.note.txt
EXPOSE 21 21100-21110

###########################
## Configuration SSH
###########################

RUN sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config \
 && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

RUN useradd -s /bin/bash -m mbappinho \
 && echo "mbappinho:realmadrid" | chpasswd \
 && echo "root:Blondasse-Embrassee7" | chpasswd

RUN useradd -s /bin/bash -m dinohh \
 && echo "dinohh:C0sm03nj0Y3R!75811$" | chpasswd

RUN chmod 700 /home/dinohh
RUN chmod 700 /home/mbappinho

RUN mkdir -p /home/dinohh/.ssh && chmod 700 /home/dinohh/.ssh
COPY ssh_keys/id_rsa /home/dinohh/.ssh/id_rsa
COPY ssh_keys/id_rsa.pub /home/dinohh/.ssh/id_rsa.pub

RUN cat /home/dinohh/.ssh/id_rsa.pub > /home/dinohh/.ssh/authorized_keys

RUN chmod 600 /home/dinohh/.ssh/id_rsa /home/dinohh/.ssh/authorized_keys \
 && chown -R dinohh:dinohh /home/dinohh/.ssh

EXPOSE 22

###########################
## Configuration APACHE
###########################

RUN a2enmod rewrite
COPY apache-config.conf /etc/apache2/sites-available/my-site.conf
RUN a2dissite 000-default && a2ensite my-site

COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html
COPY flags/flag1.txt /var/www/html/flag.txt
EXPOSE 80

RUN mkdir /srv/scripts && chmod 700 /srv/scripts
COPY scripts/get_tickets.py /srv/scripts/get_tickets.py
COPY root.crontab /tmp/root.crontab
RUN crontab /tmp/root.crontab && rm /tmp/root.crontab

###########################
## Configuration LSHELL
###########################

RUN pip install limited-shell --break-system-packages
RUN cp /usr/local/bin/lshell /bin/lshell
RUN echo "/bin/lshell" | tee -a /etc/shells
COPY lshell.conf /etc/lshell.conf
RUN rm /usr/local/etc/lshell.conf
RUN chsh -s /bin/lshell mbappinho
COPY flags/flag2.txt /home/mbappinho/flag.txt

###########################
## SCRIPT Lateral PE
###########################

RUN mkdir /opt/dingz && mkdir /opt/dingz/__pycache__
COPY scripts/roulette.py /opt/dingz/roulette.py
RUN chown dinohh:dinohh /opt/dingz/roulette.py && chmod 755 /opt/dingz/roulette.py && chmod 777 /opt/dingz
RUN echo "mbappinho ALL=(dinohh) NOPASSWD: /usr/bin/python3 /opt/dingz/roulette.py" > /etc/sudoers.d/mbappinho
COPY flags/flag3.txt /home/dinohh/flag.txt

###########################
## PRIVILEGE ESCALATION
###########################

RUN echo "dinohh ALL=(ALL, !root) NOPASSWD: /usr/bin/sed" > /etc/sudoers.d/dinohh
COPY bash_history_dinohh.txt /home/dinohh/.bash_history
RUN chown root:root /home/dinohh/.bash_history \
    && chmod 444 /home/dinohh/.bash_history
COPY flags/flag4.txt /root/flag.txt
COPY root_note.txt /root/note.txt

###########################
## SCRIPT LANCEMENT
###########################

COPY entrypoint.sh /root/.entrypoint.sh
RUN chmod +x /root/.entrypoint.sh
CMD ["/root/.entrypoint.sh"]