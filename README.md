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
"stickybeat/lravel-audiosalad": "dev-master"
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
```

#### Instructions

Intructions on autorization and endpoints can be found in AudioSalad's dashboard for tha API.

There is a rate limit set on the API.
Requests are limited to 200 per minute by default. If you exceed the rate limit you will receive an error response with an HTTP code of 429 and "Api Request limit exceeded" in the body. There will also be a Retry-After header that will indicate when a successful request may be executed.
