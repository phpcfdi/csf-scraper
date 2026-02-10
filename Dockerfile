# Stage 1: Build dependencies
FROM composer:2 AS builder

WORKDIR /app

# Copy composer files for better caching
COPY composer.json ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# Stage 2: Production image
FROM php:8.2-alpine

LABEL maintainer="PhpCfdi"
LABEL description="Producción image for csf-scraper library"

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    poppler-utils \
    libxml2-dev \
    oniguruma-dev \
    libcurl \
    curl-dev

RUN docker-php-ext-install \
    curl \
    mbstring \
    opcache \
    dom

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Create a non-root user
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

WORKDIR /app

# Copy code and vendor from builder
COPY --from=builder /app/vendor /app/vendor
COPY ./src /app/src
COPY ./bin /app/bin
COPY ./docs /app/docs
COPY ./composer.json /app/composer.json

# Adjust permissions
RUN chown -R appuser:appgroup /app

# Switch to non-root user
USER appuser

# Essential for the library (pdftotext is part of poppler-utils)
ENV PATH="/usr/bin:${PATH}"

ENTRYPOINT ["php", "/app/bin/csf-scraper"]
