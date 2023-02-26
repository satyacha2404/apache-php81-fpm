FROM satyachandra/apache-php81-fpm:v9

COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/php.ini /etc/php/8.1/fpm/php.ini
COPY config/30-docker-php-ext-skywalking_agent.ini /etc/php/8.1/fpm/conf.d/30-docker-php-ext-skywalking_agent.ini

RUN chmod 777 -R /var/
RUN chown -R www-data:www-data /var
