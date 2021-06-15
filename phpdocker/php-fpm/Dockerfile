FROM phpdockerio/php80-fpm:latest
WORKDIR "/web"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.0-mysql php8.0-redis php8.0-sqlite3 php8.0-xdebug php8.0-gd \
    && chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions gd xdebug \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ../nginx/nginx.conf /etc/nginx/conf.d/default.conf.template

CMD /bin/bash -c "envsubst '\$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf" && nginx -g 'daemon off;'
