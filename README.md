# BigCommerce OAuth for Laravel

A lightweight, drop‑in Laravel package that implements the full BigCommerce OAuth flow (install, load, uninstall, remove user). Designed to work in small apps and large, complex, multi‑tenant systems alike.

## Features
- Install, Load, Uninstall, Remove User routes ready to use.
- Persists store access tokens and user–store relationships.
- Simple Facade API for callbacks, session store selection, and token access.
- Laravel 10 and 11 compatible; PHP 8.2+.

## Requirements
- PHP `^8.2`
- Laravel `^10` or `^11`

## Installation
```bash
composer require cronixweb/bigcommerce-oauth-laravel
```
The service provider is auto‑discovered.

Publish config, migrations, and views into your app, then run migrations:
```bash
php artisan vendor:publish --provider="CronixWeb\\BigCommerceAuth\\BigAuthServiceProvider" --tag=config,migrations,views
php artisan migrate
```

## Configure
Set the following environment variables in your Laravel app:
```
BC_CLIENT_ID=your_client_id
BC_SECRET=your_client_secret
BC_REDIRECT_PATH=/       # where to redirect after successful install/load
```
You can also override defaults in `config/bigcommerce-auth.php` (tables, controllers, views).

## Routes
This package ships a route file with the following endpoints (prefixed by `auth`):
- `GET /auth/install` → handles OAuth installation
- `GET /auth/load` → verifies signed payload and logs user in
- `GET /auth/uninstall` → uninstall callback
- `GET /auth/remove_user` → remove user callback

Register the package routes once in your application (e.g., in `routes/web.php` or a service provider):
```php
use CronixWeb\BigCommerceAuth\BigAuthRoutes;

BigAuthRoutes::register();
```

In the BigCommerce Developer Portal, set your app callback URLs to your app domain:
- Auth Callback URL: `https://your-app.com/auth/install`
- Load Callback URL: `https://your-app.com/auth/load`
- Uninstall Callback URL: `https://your-app.com/auth/uninstall`
- Remove User Callback URL: `https://your-app.com/auth/remove_user`

## Database
Published migrations create:
- `stores` (id, hash, access_token, timestamps, softDeletes)
- `store_has_users` (store_id, user_id, timestamps, composite PK)
- Updates `users` to allow nullable `name` and `password` (for SSO‑style accounts)

The default Eloquent model is `CronixWeb\BigCommerceAuth\Models\Store` and can be swapped via config.

## Usage
Hook into lifecycle callbacks and access tokens via the Facade:
```php
use CronixWeb\BigCommerceAuth\Facades\BigCommerceAuth as BC;

// In a service provider's boot():
BC::setInstallCallback(function ($user, $store) {
    // e.g., provision tenant resources, set roles
});

BC::setLoadCallback(function ($user, $store) {
    // e.g., initialize per-request context, analytics
});

BC::setUninstallStoreCallBack(function ($payload) {
    // e.g., revoke tokens, archive data for $payload['store_hash']
});

BC::setRemoveStoreUserCallBack(function ($payload) {
    // e.g., detach user from store
});

// Later in your app code:
// Ensure the store hash is set (done during load/install), then get the token
$token = BC::getStoreAccessToken();
$store = BC::store(); // current store Eloquent record
```

Advanced: You can override how the access token is resolved or how the current store is found using `setGetStoreAccessTokenCallback()` and `setFindStoreFromSessionCallBack()`.

## Security Notes
- Keep `BC_CLIENT_ID` and `BC_SECRET` in env; never commit secrets.
- Production requests verify TLS by default; development uses `Http::withoutVerifying()`.
- Treat `stores.access_token` as sensitive; avoid logging.

## Contributing
We follow Conventional Commits (e.g., `feat:`, `fix:`, `refactor:`). Please include a clear description, note schema/config changes, and add tests where possible. Open issues or PRs are welcome.

## License
MIT
