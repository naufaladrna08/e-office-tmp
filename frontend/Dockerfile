# ======= BUILD  STAGE ======= #
FROM node:lts-alpine as build-stage

WORKDIR /app
COPY package*.json ./

RUN npm install
COPY . .

RUN npm run build
RUN sed -i 's/http:\/\/127.0.0.1:8000\/api/http:\/\/api.dpcsppibersatuptp.online\/api/g' ./src/main.js

# ===== PRODUCTION STAGE ===== #
FROM ubuntu/apache2:latest as production-stage

COPY --from=build-stage /app/dist /var/www/html

# Creating .htaccess
COPY htaccess /var/www/html/.htaccess

# Enabling Httpd Configurations (Rewrite)
RUN a2enmod rewrite
RUN rm /etc/apache2/apache2.conf
COPY external/apache2.conf /etc/apache2/apache2.conf
RUN service apache2 restart

EXPOSE 80