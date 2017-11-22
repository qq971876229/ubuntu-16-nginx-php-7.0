#FROM ubuntu:16.04
FROM krallin/ubuntu-tini:16.04
MAINTAINER 971876229@qq.com
COPY files /
RUN \
    apt-get update  && \
    apt-get install -y php7.0-bcmath php7.0-bz2 php7.0-cli php7.0-common php7.0-curl php7.0-dba  php7.0-fpm php7.0-gd php7.0-gmp php7.0-imap php7.0-intl php7.0-ldap php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-odbc php7.0-pgsql php7.0-recode php7.0-snmp php7.0-soap php7.0-sqlite php7.0-tidy php7.0-xml php7.0-xmlrpc php7.0-xsl php7.0-zip && \
    apt-get install -y php-gnupg php-imagick php-mongodb php-redis php-dev php-streams php-fxsl && \

    #composer
    curl -sS https://getcomposer.org/installer | php  && \
    /usr/bin/php composer.phar --version && \
    mv composer.phar /usr/local/bin/composer && \
    apt-get install -y snmp-mibs-downloader && \

    #swoole
    pecl install  swoole && \

    apt-get install -y vim && \

    #ssh
    apt-get install -y openssh-server && \
    chmod 600 /root/.ssh/authorized_keys && \

    apt-get install -y git && \

    #phalcon
    curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | bash && \
    apt-get install php7.0-phalcon && \

    #ftp
    apt-get install -y vsftpd && \
    mkdir /home/ftp && \
    useradd -d /home/ftp -s /bin/bash ftpwxx  && \
    echo 'ftpwxx:woshi213' |chpasswd && \

    #crontab -l crontab -e
    apt-get install cron && \

    echo 'root:woshi213' |chpasswd && \

    apt-get install -y nginx && \

    chmod 755 /start.sh


EXPOSE 3306

ENTRYPOINT ["top", "-b"]
#ENTRYPOINT ["/usr/local/bin/tini", "--", "top"]
#CMD ["-c"]

#CMD ["/bin/echo", "his is a echo test "]
#CMD ["nginx", "-g", "daemon off;"]

#CMD ["/start.sh"]