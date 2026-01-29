FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy application files to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 (Apache default)
EXPOSE 80

# The base image starts Apache automatically
