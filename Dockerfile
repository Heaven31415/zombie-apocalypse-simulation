FROM php:8.2.7-alpine3.18

# Add necessary PHP extensions for PostgreSQL
RUN set -ex \
	&& apk add --no-cache postgresql-libs postgresql-dev \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

# Install composer to /bin directory
WORKDIR /bin
COPY install-composer.sh .
RUN ./install-composer.sh && rm install-composer.sh && mv composer.phar composer

# Install Symfony CLI
RUN apk add --no-cache bash curl sudo
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | sudo -E bash
RUN apk add symfony-cli

# Create a new user and make him the owner of the /app directory
RUN addgroup user && adduser -S -G user user
WORKDIR /app
COPY . .
RUN chown -R user /app
USER user

# Install project dependencies
RUN composer install
EXPOSE 8000

CMD symfony serve
