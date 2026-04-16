# Install PHP & JS dependencies
composer install
npm install

# Setup environment
cp .env.example .env

update this lines

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=taskmanager

DB_USERNAME=root

DB_PASSWORD=

php artisan key:generate

# Run migrations
php artisan migrate

# Run terminal
Terminal 1: php artisan serve

Terminal 2: npm run dev

# Checklist

Project CRUD: Create a project, then try renaming or deleting it.

Task Drag-and-Drop: Drag a task to a new position

Filtering: Change the project in the dropdown; only tasks belonging to that project should appear.

Cascade Delete: Delete a project and verify (via database or UI) that its tasks were also removed.