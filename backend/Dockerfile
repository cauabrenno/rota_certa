FROM webdevops/php-nginx:8.2
ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
WORKDIR /app
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Limpa atalhos antigos quebrados e recria o link simbólico perfeitamente no Linux
RUN rm -rf public/storage && php artisan storage:link

# Dá permissão total para o servidor acessar as pastas
RUN chown -R application:application /app/storage /app/bootstrap/cache /app/public