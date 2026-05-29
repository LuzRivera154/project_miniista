FROM php:apache

COPY --chown=www-data:www-data . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/uploads

RUN echo "upload_max_filesize=200M\npost_max_size=210M" \
    > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80

VOLUME /var/www/html/uploads
