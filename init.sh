# Install Composer packages without any interaction or console log
composer install --no-interaction --quiet

# .env file initialization
{
    echo 'APP_NAME="Laravel beadandó"'
    echo 'APP_ENV=local'
    echo 'APP_KEY='
    echo 'APP_DEBUG=true'

    # Change this if needed
    echo 'APP_TIMEZONE=UTC'
    
    echo 'APP_URL=http://localhost:8000'
    echo ''
    echo 'DB_CONNECTION=sqlite'

    # Change this if needed
    echo 'FILESYSTEM_DISK=local'
} > .env

# Generate the encryption key
php artisan key:generate

# Install frontend packages with Node Package Manager (without console log)
npm install --silent

# Generate frontend assets. With the build option Vite would run with watch mode,
# and it would stall the init process. That's why we use Mix.
npm run dev -- build

# Create an empty database\database.sqlite file, so we can run the migrations
touch database/database.sqlite

# Running migrations and seed the database
php artisan migrate:fresh --seed

# There is a possibility, that the public folder in the storage folder is missing,
# to create a symlink, we need at least an empty folder.
mkdir ./storage/app/public

# Create symlink, default: /public/storage ==> /storage/app/public
php artisan storage:link

# Run dev server
php artisan serve
