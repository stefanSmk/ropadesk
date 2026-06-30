FROM php:8.2-cli-alpine
WORKDIR /app
COPY . .
EXPOSE 8080
ENV ROPADESK_API_KEY=change-me
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
