FROM php:8.2-apache

# Instalar pacotes necessários e extensões PHP
RUN apt-get update && \
    apt-get install -y vim git unzip libpng-dev libjpeg-dev libfreetype6-dev && \
    apt-get install -y libzip-dev openssl && \
    docker-php-ext-install pdo pdo_mysql gd zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/

# Clonar o repositório
RUN git clone https://github.com/Gabrielkoeric/gerenciador_de_maquinas.git

RUN cp /var/www/html/gerenciador_de_maquinas/.env.example /var/www/html/gerenciador_de_maquinas/.env

# Atualizar a configuração do Apache para o site principal
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/gerenciador_de_maquinas/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/gerenciador_de_maquinas/public>|' /etc/apache2/apache2.conf

# Atualizar a configuração do Apache para o SSL
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/gerenciador_de_maquinas/public|' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/gerenciador_de_maquinas/public>|' /etc/apache2/apache2.conf

# Gerar o certificado SSL autoassinado
RUN mkdir -p /etc/ssl/certs /etc/ssl/private && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout /etc/ssl/private/ssl-cert-snakeoil.key \
        -out /etc/ssl/certs/ssl-cert-snakeoil.pem \
        -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=localhost"

# Habilitar o SSL e o site SSL
RUN a2enmod ssl && \
    a2ensite default-ssl

WORKDIR /var/www/html/gerenciador_de_maquinas

# Instalar dependências do Composer, ignorando as dependências do Laravel Horizon
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Gerar a chave da aplicação
RUN php artisan key:generate --force

# Definir as permissões corretas para storage e cache
RUN chown -R www-data:www-data /var/www/html/gerenciador_de_maquinas/storage /var/www/html/gerenciador_de_maquinas/bootstrap/cache

# Habilitar o módulo mod_rewrite do Apache
RUN a2enmod rewrite

# Expor as portas 80 e 443
EXPOSE 80 443

CMD ["apache2-foreground"]
