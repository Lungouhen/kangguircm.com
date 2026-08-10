# Suggested GitHub Actions Security Workflow

The connected GitHub App cannot publish workflow files. When a repository administrator enables GitHub Actions workflow permissions, copy the YAML below to `.github/workflows/security.yml`.

```yaml
name: Security checks

on:
  pull_request:
  push:
    branches: [main]
  schedule:
    - cron: '17 4 * * 1'

permissions:
  contents: read

jobs:
  audit-and-test:
    runs-on: ubuntu-latest
    timeout-minutes: 15
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          coverage: none
      - uses: actions/setup-node@v4
        with:
          node-version: '22'
          cache: npm
      - run: composer install --no-interaction --prefer-dist --no-progress
      - run: composer audit
      - run: npm ci
      - run: npm run security:audit
      - run: npm run project:audit
      - run: npm run seo:audit
      - run: npm run build
      - run: cp .env.example .env && php artisan key:generate
      - run: php artisan test
        env:
          DB_CONNECTION: sqlite
          DB_DATABASE: ':memory:'
          APP_ENV: testing
          APP_DEBUG: false
```
