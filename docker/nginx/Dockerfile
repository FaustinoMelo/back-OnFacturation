FROM nginx:latest

# Remove default Nginx config
RUN rm /etc/nginx/conf.d/default.conf

# Copy our custom config
COPY default.conf /etc/nginx/conf.d/default.conf

# Copy static assets if needed (optional)
# COPY ./public /var/www/public

# Expose port 80
EXPOSE 80
