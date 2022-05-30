FROM php:8.1.6-fpm

# Install WGET
RUN  apt-get update \
  && apt-get install -y wget \
  && apt-get install unzip \
  && apt-get install libaio1 \
  && apt-get install -y nodejs npm yarn \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV LD_LIBRARY_PATH /usr/local/instantclient_21_6/
# Install Oracle Instantclient
RUN mkdir /opt/oracle
COPY /docker/php/instantclient-basiclite-linux.x64-21.6.0.0.0dbru.zip /opt/oracle/
COPY /docker/php/instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip /opt/oracle/
RUN cd /opt/oracle \
    #&& wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-basiclite-linux.x64-21.6.0.0.0dbru.zip \
    #&& wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip \
    && unzip /opt/oracle/instantclient-basiclite-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && unzip /opt/oracle/instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && rm -rf /opt/oracle/instantclient_21_6/libclntsh.so \
    && rm -rf /opt/oracle/instantclient_21_6/libclntshcore.so \
    && rm -rf /opt/oracle/instantclient_21_6/libocci.so \
    && ln -s /opt/oracle/instantclient_21_6/libclntsh.so.21.1 /opt/oracle/instantclient_21_6/libclntsh.so \
    && ln -s /opt/oracle/instantclient_21_6/libclntshcore.so.21.1 /opt/oracle/instantclient_21_6/libclntshcore.so \
    && ln -s /opt/oracle/instantclient_21_6/libocci.so.21.1 /opt/oracle/instantclient_21_6/libocci.so \
    && rm -rf /opt/oracle/*.zip \
    && echo /opt/oracle/instantclient_21_6 > /etc/ld.so.conf.d/oracle-instantclient.conf && \
    ldconfig

# Install Oracle extensions
RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient_21_6,21_6 \
       && echo 'instantclient,/opt/oracle/instantclient_21_6/' | pecl install oci8 \
       && docker-php-ext-install \
               pdo_oci \
       && docker-php-ext-enable \
               oci8

# Install XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug