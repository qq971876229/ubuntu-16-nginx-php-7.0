FROM ubuntu:16.04
MAINTAINER 971876229@qq.com
COPY files /
RUN \
    apt-get update  && \
#    apt-get install -y php7.0 && \
    apt-get install -y php7.0-bcmath php7.0-bz2 php7.0-cli php7.0-common php7.0-curl php7.0-dba  php7.0-fpm php7.0-gd php7.0-gmp php7.0-imap php7.0-intl php7.0-ldap php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-odbc php7.0-pgsql php7.0-recode php7.0-snmp php7.0-soap php7.0-sqlite php7.0-tidy php7.0-xml php7.0-xmlrpc php7.0-xsl php7.0-zip && \
    apt-get install -y php-gnupg php-imagick php-mongodb php-redis php-dev php-streams php-fxsl && \
#    pecl install  swoole && \

#    curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | bash && \
#    apt-get install php7.0-phalcon && \

    apt-get install -y vim && \

    apt-get install -y openssh-server && \
    chmod 600 /root/.ssh/authorized_keys && \

    apt-get install -y git && \



    apt-get install -y vsftpd && \
    mkdir /home/ftp && \
    useradd -d /home/ftp -s /bin/bash ftpwxx  && \
    echo 'ftpwxx:woshi213' |chpasswd && \

    apt-get install cron && \

    echo 'root:woshi213' |chpasswd && \

    apt-get install -y nginx && \

    chmod 755 /start.sh

#    mkdir /tmp/composer/ && \
#    cd /tmp/composer && \
#    curl -sS https://getcomposer.org/installer | php && \
#    mv composer.phar /usr/local/bin/composer && \
#    chmod a+x /usr/local/bin/composer && \
#    cd / && \
#    rm -rf /tmp/composer && \
#    apt-get autoremove -y && \
#    rm -rf /var/lib/apt/lists/* && \
#    rm -rf /etc/nginx/sites-enabled/default /etc/nginx/sites-available/* && \
#    sed -i -e 's/^user = www-data$/;user = www-data/g' /etc/php/7.0/fpm/pool.d/www.conf && \
#    sed -i -e 's/^group = www-data$/;group = www-data/g' /etc/php/7.0/fpm/pool.d/www.conf && \
#    sed -i -e 's/^listen.owner = www-data$/;listen.owner = www-data/g' /etc/php/7.0/fpm/pool.d/www.conf && \
#    sed -i -e 's/^listen.group = www-data$/;listen.group = www-data/g' /etc/php/7.0/fpm/pool.d/www.conf && \
#    sed -i -e 's/max_execution_time = 30/max_execution_time = 300/g' /etc/php/7.0/fpm/php.ini && \
#    sed -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 256M/g' /etc/php/7.0/fpm/php.ini && \
#    sed -i -e 's/post_max_size = 8M/post_max_size = 512M/g' /etc/php/7.0/fpm/php.ini && \
#    sed -i -e 's/memory_limit = 128M/memory_limit = 512M/g' /etc/php/7.0/fpm/php.ini && \
#    sed -i -e 's/fastcgi_param  SERVER_PORT        $server_port;/fastcgi_param  SERVER_PORT        $http_x_forwarded_port;/g' /etc/nginx/fastcgi.conf && \
#    sed -i -e 's/fastcgi_param  SERVER_PORT        $server_port;/fastcgi_param  SERVER_PORT        $http_x_forwarded_port;/g' /etc/nginx/fastcgi_params && \
#    sed -i -e '/sendfile on;/a\        fastcgi_read_timeout 300\;' /etc/nginx/nginx.conf && \
#    mkdir -p /usr/src/tmp/ioncube && \
#    curl -fSL "http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz" -o /usr/src/tmp/ioncube_loaders_lin_x86-64.tar.gz && \
#    tar xfz /usr/src/tmp/ioncube_loaders_lin_x86-64.tar.gz -C /usr/src/tmp/ioncube && \
#    cp /usr/src/tmp/ioncube/ioncube/ioncube_loader_lin_7.0.so /usr/lib/php/20151012/ && \
#    rm -rf /usr/src/tmp/ && \
#    mkdir --mode 777 /var/run/php && \
#    chmod 755 /hooks /var/www && \
#    chmod -R 777 /var/www/html /var/log && \
#    sed -i -e 's/index index.html/index index.php index.html/g' /etc/nginx/sites-enabled/site.conf && \
#    chmod 666 /etc/nginx/sites-enabled/site.conf && \
#    mkdir -p /run /var/lib/nginx /var/lib/php && \
#    chmod -R 777 /run /var/lib/nginx /var/lib/php /etc/php/7.0/fpm/php.ini


EXPOSE 3306

#ENTRYPOINT ["top", "-b"]
#ENTRYPOINT nginx
ENTRYPOINT ["nginx", "-g", "daemon off;"]
#CMD ["-c"]

#CMD ["/bin/echo", "his is a echo test "]
#CMD ["nginx", "-g", "daemon off;"]

#CMD ["/start.sh"]
#CMD ["/bin/bash/nginx"]