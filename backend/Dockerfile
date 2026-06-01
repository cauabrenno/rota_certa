FROM webdevops/php-nginx:8.2
ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
WORKDIR /app
COPY . .
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R application:application /app/storage /app/bootstrap/cache