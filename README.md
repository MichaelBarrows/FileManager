# File Manager

## Installation
To install and run this project:
```
    git clone https://github.com/MichaelBarrows/file-manager
    cd file-manager
    cp .env.example .env
    composer install
    npm install
```
Add database and AWS details to ```.env``` (note, all `AWS_` fields are required).
```
    php artisan key:generate
    php artisan migrate
```
