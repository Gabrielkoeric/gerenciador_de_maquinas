FROM php:8.2-apache

# Instalar pacotes necessários e extensões PHP
RUN apt-get update && \
    apt-get install -y vim git unzip libpng-dev libjpeg-dev libfreetype6-dev && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install pdo pdo_mysql gd zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/

# Clonar o repositório
RUN git clone https://github.com/Gabrielkoeric/gerenciador_de_maquinas.git

RUN cp /var/www/html/gerenciador_de_maquinas/.env.example /var/www/html/gerenciador_de_maquinas/.env

# Atualizar a configuração do Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/gerenciador_de_maquinas/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/gerenciador_de_maquinas/public>|' /etc/apache2/apache2.conf

WORKDIR /var/www/html/gerenciador_de_maquinas

# Instalar dependências do Composer
#RUN composer install --no-dev --optimize-autoloader

# Instalar dependências do Composer, ignorando as dependências do Laravel Horizon
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Gerar a chave da aplicação
RUN php artisan key:generate --force

# Definir as permissões corretas para storage e cache
RUN chown -R www-data:www-data /var/www/html/gerenciador_de_maquinas/storage /var/www/html/gerenciador_de_maquinas/bootstrap/cache

# Habilitar o módulo mod_rewrite do Apache
RUN a2enmod rewrite

# Expor a porta 80
EXPOSE 80 

CMD ["apache2-foreground"]
