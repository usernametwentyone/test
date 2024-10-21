### 1. Copy .env file

```bash
cp .env.example .env
```

### 2. Install dependencies

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Run the application

```bash
./vendor/bin/sail up
```

### 4. Key generate

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Migrate database

```bash
./vendor/bin/sail artisan migrate
```

### 6. Seed database

```bash
./vendor/bin/sail artisan db:seed
```

### 7. Build assets

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

URL: http://localhost

User: test@example.com
Pass: password
