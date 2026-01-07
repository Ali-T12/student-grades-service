FROM php:8.2-cli

WORKDIR /app

# Install needed PHP extensions for MySQL
RUN docker-php-ext-install pdo_mysql mysqli

# Copy project
COPY . .

# Railway sets PORT automatically, locally we default to 8080
ENV PORT=8080

# Run PHP built-in server and point to your public folder
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t student-grades/public"]