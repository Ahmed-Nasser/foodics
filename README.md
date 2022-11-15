# foodics-Task

### Installation
* Clone repository to your machine:
```bash
    git clone https://github.com/Ahmed-Nasser/foodics.git
```

* Install dependencies:
```bash
    composer install
```

* Configure Environment:
```bash
    cp .env.example .env
    php artisan key:generate
```

* Migrate & Seed the Database:
```bash
    php artisan migrate --seed    
```

* Configure the Queues for sending emails:
```bash
    php artisan queue:table
    php artisan migrate
    php artisan queue:listen    
```
