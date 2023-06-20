FROM php:8.1-apache


RUN docker-php-ext-install mysqli
# Install PDO MySQL driver
RUN docker-php-ext-install pdo_mysql

# Copy your application code
COPY . /var/www/html/

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html