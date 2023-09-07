<?php

return [
  "host" => env(
    "AUDIOSALAD_HOST",
    "https://bellpartners.dashboard.audiosalad.com/client-api/"
  ),
  "access_id" => env("AUDIOSALAD_ACCESS_ID", ""),
  "refresh_token" => env("AUDIOSALAD_REFRESH_TOKEN", ""),
  "timeout" => env("AUDIOSALAD_TIMEOUT", 5),
  "bucket" => env("AUDIOSALAD_S3_BUCKET", ""),
  "accessId" => env("AUDIOSALAD_S3_ACCESS_KEY_ID", ""),
  "secretKey" => env("AUDIOSALAD_S3_SECRET_ACCESS_KEY", ""),
  "path" => env("AUDIOSALAD_S3_PATH", ""),
];
