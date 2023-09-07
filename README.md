## AudioSalad API Client for Laravel

Package to integrate with AudioSalad REST API

#### Requirements

- Laravel 6 or higher
- PHP 8.0 or higher
- Valid access id ( from AudioSalad)
- Valid refresh token (from AudioSalad)
- S3 bucket name
- S3 access key
- S3 secret key
- S3 region

---

#### Installation

Since this package is not public you have to add the repository under "repositories" in your composer.json file.

```
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/StickyBeat/laravel-audiosalad.git"
  }
]
```

Then you can add the package to required packages

```
"stickybeat/laravel-audiosalad": "dev-master"
```

---

#### Settings for .env

```
AUDIOSALAD_ACCESS_ID=
AUDIOSALAD_REFRESH_TOKEN=
AUDIOSALAD_S3_BUCKET_NAME=
AUDIOSALAD_S3_ACCESS_KEY_ID=
AUDIOSALAD_S3_SECRET_ACCESS_KEY=
AUDIOSALAD_S3_DEFAULT_REGION=
AUDIOSALAD_S3_PATH=
```

#### Instructions

In order to use the AudioSalad API, you need a valid refresh token. To get a refresh token, you need to access the API dashboard at AudioSalad and generate one.

The **access token** - which is used to authenticate all API request - is only valid for 1 hour and needs to be refreshed using a refresh token. This package handles that process as long you have a valid refresh token.

The **refresh token** - which is used to renew the access token - is valid for 24 hours. If it expires, you need to generate a new token in the dashboard.

Every time you renew you access token, you will be assigned a new refresh token that is valid for another 24 hours. It is therefore important that you regularly renew the tokens so that the refresh token does not expire.

You can renew your tokens with the following command:

```
php artisan audiosalad:refresh
```

To get information on current tokens, use the following command:

```
php artisan audiosalad:tokens
```

You can also purge all tokens. **Observe! If you do this you need to add a valid refresh token to .env to be able to get a new valid access token.** To purge tokens, use the following command:

```
php artisan audiosalad:purge
```

Intructions on endpoints can be found in AudioSalad's dashboard for the API.

There is a rate limit set on the API.
Requests are limited to 200 per minute by default. If you exceed the rate limit you will receive an error response with an HTTP code of 429 and "Api Request limit exceeded" in the body. There will also be a Retry-After header that will indicate when a successful request may be executed.
