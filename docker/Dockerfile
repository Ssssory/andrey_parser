FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

RUN apt-get update -y && apt-get install -y openssl zip unzip git curl libonig-dev mc vim htop supervisor cron
RUN docker-php-ext-install pdo pdo_mysql mbstring

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

COPY crontab/www-crontab /etc/cron.d/www-crontab

RUN touch /var/log/supervisor/supervisord.log
RUN chmod 0766 /var/log/supervisor/supervisord.log
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
RUN chmod 0766 /var/log/cron.log

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]