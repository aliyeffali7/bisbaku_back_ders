#!/bin/bash

# Quick Deployment Script for Contabo Server
# Run this after connecting via SSH

echo "🚀 Starting Laravel Deployment..."

# Step 1: Update system
echo "📦 Updating system..."
sudo apt update && sudo apt upgrade -y

# Step 2: Install PHP and extensions
echo "📦 Installing PHP 8.2..."
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath

# Step 3: Install Composer
echo "📦 Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

# Step 4: Install MySQL
echo "📦 Installing MySQL..."
sudo apt install -y mysql-server

# Step 5: Install Nginx
echo "📦 Installing Nginx..."
sudo apt install -y nginx

# Step 6: Install Git
echo "📦 Installing Git..."
sudo apt install -y git

# Step 7: Clone repository
echo "📥 Cloning repository..."
cd /var/www
if [ -d "bisbaku-back" ]; then
    echo "⚠️  Directory exists, pulling latest..."
    cd bisbaku-back
    git pull
else
    git clone https://github.com/rustammgasanovv/bisbaku-back.git
    cd bisbaku-back
fi

# Step 8: Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Step 9: Create .env if not exists
if [ ! -f ".env" ]; then
    echo "⚙️  Creating .env file..."
    cp .env.example .env
    echo "⚠️  Please edit .env file with your database credentials!"
    echo "Run: nano .env"
fi

# Step 10: Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data /var/www/bisbaku-back
sudo chmod -R 755 /var/www/bisbaku-back
sudo chmod -R 775 /var/www/bisbaku-back/storage
sudo chmod -R 775 /var/www/bisbaku-back/bootstrap/cache

echo "✅ Basic setup complete!"
echo ""
echo "📝 Next steps:"
echo "1. Edit .env file: nano .env"
echo "2. Generate app key: php artisan key:generate"
echo "3. Run migrations: php artisan migrate"
echo "4. Configure Nginx (see CONTABO_DEPLOYMENT_GUIDE.md)"
echo "5. Test: http://194.163.173.179/api/courses"

