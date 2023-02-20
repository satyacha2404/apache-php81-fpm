FROM satyachandra/apache-php81-fpm:v7

COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/php.ini /etc/php/8.1/fpm/php.ini
COPY config/openssl.cnf /etc/ssl/openssl.cnf
COPY config/openssl.cnf /usr/lib/ssl/openssl.cnf
COPY config/30-docker-php-ext-skywalking_agent.ini /etc/php/8.1/fpm/conf.d/30-docker-php-ext-skywalking_agent.ini

RUN chmod 777 -R /var/
RUN chown www-data:www-data /var
RUN a2enmod rewrite expires vhost_alias headers
RUN a2ensite 000-default.conf