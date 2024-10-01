# Use PHP with Apache
FROM php:8.2-apache

# Install necessary PHP extensions and enable Apache modules
RUN docker-php-ext-install pdo pdo_mysql mbstring
RUN a2enmod rewrite
RUN a2enmod actions

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get -y update
RUN chmod 755 /usr/bin/composer
RUN apt-get install -y git

# Change the Apache DocumentRoot to the 'public' directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable .htaccess support by setting AllowOverride All
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV PHP_EXTENSION_CALENDAR=1 \
    PHP_EXTENSION_CTYPE=1 \
    PHP_EXTENSION_CURL=1 \
    PHP_EXTENSION_DOM=1 \
    PHP_EXTENSION_EXIF=1 \
    PHP_EXTENSION_FILEINFO=1 \
    PHP_EXTENSION_FTP=1 \
    PHP_EXTENSION_GETTEXT=1 \
    PHP_EXTENSION_ICONV=1 \
    PHP_EXTENSION_JSON=1 \
    PHP_EXTENSION_MBSTRING=1 \
    PHP_EXTENSION_OPCACHE=1 \
    PHP_EXTENSION_PDO=1 \
    PHP_EXTENSION_PHAR=1 \
    PHP_EXTENSION_POSIX=1 \
    PHP_EXTENSION_READLINE=1 \
    PHP_EXTENSION_SHMOP=1 \
    PHP_EXTENSION_SIMPLEXML=1 \
    PHP_EXTENSION_SOCKETS=1 \
    PHP_EXTENSION_SYSVMSG=1 \
    PHP_EXTENSION_SYSVSEM=1 \
    PHP_EXTENSION_SYSVSHM=1 \
    PHP_EXTENSION_TOKENIZER=1 \
    PHP_EXTENSION_WDDX=1 \
    PHP_EXTENSION_XML=1 \
    PHP_EXTENSION_XMLREADER=1 \
    PHP_EXTENSION_XMLWRITER=1 \
    PHP_EXTENSION_XSL=1 \
    PHP_EXTENSION_ZIP=1

# Expose port 80 to the host
EXPOSE 80
EXPOSE 9003

# Start Apache in the foreground
CMD ["apache2-foreground"]
