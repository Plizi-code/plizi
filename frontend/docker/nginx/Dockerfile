FROM nginx:stable
COPY public /app
COPY docker/nginx/default.example.conf /etc/nginx/default.conf
RUN	chown -R www-data:www-data /app
