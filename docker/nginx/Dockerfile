FROM nginx:1.15.8-alpine

COPY docker/nginx/conf/360_vhost.conf /etc/nginx/conf.d/default.conf
RUN rm /var/log/nginx/*.log

WORKDIR /var/www/html

EXPOSE 80