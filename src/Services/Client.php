<?php

namespace StickyBeat\AudioSalad\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Psr\Http\Message\RequestInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use StickyBeat\AudioSalad\Interfaces\ClientInterface;
use StickyBeat\AudioSalad\Exceptions\AudioSaladException;

class Client implements ClientInterface
{
  protected $client;

  protected $retryQueue = [];

  /**
   * Constructs a new instance.
   */
  public function __construct()
  {
    $this->client = Http::baseUrl($this->getHost())
      ->timeout($this->getTimeout())
      ->withHeaders([
        "X-Access-Id" => $this->getAccessId(),
      ])
      ->withToken($this->getAccessToken())
      ->retry(2, 400, function (Exception $exception, PendingRequest $request) {
        if (
          !$exception instanceof RequestException ||
          $exception->response->status() !== 401
        ) {
          return false;
        }

        $request->withToken($this->getAccessToken(true));

        return true;
      })
      ->asJson()
      ->acceptJson();
  }

  /**
   * Sends GET request.
   *
   * @param string $endpoint
   * @param array  $data
   * @param array  $filter
   *
   * @return mixed
   */
  public function get(
    string $endpoint,
    array $data = [],
    array $filter = []
  ): mixed {
    $response = $this->client->get($endpoint, $data);

    if ($response->failed()) {
      $this->catchError($response);
    }

    return $response;
  }

  /**
   * Sends GET request.
   *
   * @param string $endpoint
   * @param array  $data
   * @param array  $filter
   * @param mixed  $file where to store the audio file
   *
   * @return mixed
   */
  public function sink(
    string $endpoint,
    mixed $file,
    array $data = [],
    array $filter = []
  ): mixed {
    // Make a new client with the same settings as the current one
    // We need to do this since we are going to store the response to a file
    $client = Http::baseUrl($this->getHost())
      ->timeout($this->getTimeout())
      ->withHeaders([
        "X-Access-Id" => $this->getAccessId(),
      ])
      ->withToken($this->getAccessToken())
      ->asJson()
      ->acceptJson();

    $response = $client->sink($file)->get($endpoint, $data);

    if ($response->failed()) {
      $this->catchError($response);
    }

    return $response;
  }

  /**
   * Sends PUT request.
   *
   * @param string $endpoint
   * @param array  $data
   *
   * @return mixed
   */
  public function put(string $endpoint, array $data = []): mixed
  {
    $response = $this->client->put($endpoint, $data);

    if ($response->failed()) {
      $this->catchError($response);
    }

    return $response;
  }

  /**
   * Sends POST request.
   *
   * @param string $endpoint
   * @param array  $data
   * @param array  $filter
   *
   * @return mixed
   */
  public function post(
    string $endpoint,
    array $data = [],
    array $filter = []
  ): mixed {
    $response = $this->client->post($endpoint, $data);

    if ($response->failed()) {
      $this->catchError($response);
    }

    return $response;
  }

  /**
   * Send DELETE requests.
   *
   * @param string $endpoint
   * @param array  $data
   *
   * @return mixed
   */
  public function delete(string $endpoint, array $data = []): mixed
  {
    $response = $this->client->delete($endpoint, $data);

    if ($response->failed()) {
      $this->catchError($response);
    }

    return $response;
  }

  public function baseUrl(string $baseUrl): mixed
  {
    return $this->client->baseUrl($baseUrl);
  }

  public function forceRefreshTokens(): void
  {
    $this->getAccessToken(true);
  }

  public function refreshTokens(): void
  {
    $this->getAccessToken(false);
  }

  /**
   * Catch given error message from audiosalad.
   *
   * @param  \Illuminate\Http\Client\Response             $response
   *
   * @throws \StickyBeat\AudioSalad\Exceptions\AudioSaladException (description)
   *
   * @return void
   */
  protected function catchError(Response $response): void
  {
    if ($response->unauthorized()) {
      Log::debug("Unauthorized, refreshing tokens");
      $this->forceRefreshTokens();
      $this->retryQueue[] = $response->request();
      return;
    }

    if ($response->json("ErrorInformation")) {
      $message = !empty($response->json("ErrorInformation.message"))
        ? $response->json("ErrorInformation.message")
        : $response->json("ErrorInformation.Message");

      $code = !empty($response->json("ErrorInformation.code"))
        ? $response->json("ErrorInformation.code")
        : $response->json("ErrorInformation.Code");

      throw new AudioSaladException(
        sprintf("%s (%s)", $message, $code),
        $response->status()
      );
    }
  }

  /**
   * Gets the host.
   *
   * @return null|string
   */
  protected function getHost(): ?string
  {
    return config("audiosalad.host");
  }

  /**
   * Gets the client id.
   *
   * @return null|string
   */
  protected function getAccessId(): ?string
  {
    return config("audiosalad.access_id");
  }

  /**
   * Gets the access token.
   *
   * @return null|string
   */
  protected function getAccessToken(bool $forceRefresh = false): ?string
  {
    if (!$forceRefresh) {
      $storedAccessToken = Redis::get("audiosalad-access-token");
      // Check if token has expired
      if (!empty($storedAccessToken) && strlen($storedAccessToken) > 2) {
        return $storedAccessToken;
      }
    }

    // Token has expired, Refresh access token
    $refreshedToken = $this->refreshAccessToken();
    Redis::set("audiosalad-access-token", $refreshedToken, "EX", 3000); // 50 minutes

    return $refreshedToken;
  }

  /**
   * Gets the refresh token.
   *
   * @throws \StickyBeat\AudioSalad\Exceptions\FortnoxException
   *
   * @return string
   */
  protected function getRefreshToken(): string
  {
    $storedRefreshToken = Redis::get("audiosalad-refresh-token");

    if (!empty($storedRefreshToken) && strlen($storedRefreshToken) > 2) {
      Log::debug("Using stored refresh token " . $storedRefreshToken);
      return $storedRefreshToken;
    }

    if (!empty(config("audiosalad.refresh_token"))) {
      Log::debug(
        "Using config refresh token " . config("audiosalad.refresh_token")
      );
      return config("audiosalad.refresh_token");
    }

    throw new AudioSaladException("Refresh token not found or not valid");
  }

  /**
   * Gets the timeout.
   *
   * @return null|string
   */
  protected function getTimeout(): ?string
  {
    return config("audiosalad.timeout");
  }

  /**
   * Refreshes the access and refresh token.
   *
   * @throws \StickyBeat\AudioSalad\Exceptions\FortnoxException
   *
   * @return string
   */
  protected function refreshAccessToken(): string
  {
    $response = Http::baseUrl($this->getHost())
      ->withHeaders([
        "X-Access-Id" => $this->getAccessId(),
      ])
      ->timeout($this->getTimeout())
      ->asJson()
      ->post("access-token", [
        "refresh_token" => $this->getRefreshToken(),
      ]);

    if ($response->failed()) {
      throw new AudioSaladException(
        "Failed to refresh token. " .
          $response->status() .
          " " .
          $response->body()
      );
    }

    if (empty($response->json("access_token"))) {
      throw new AudioSaladException(
        "Failed to retrieve access token from response."
      );
    }

    if (empty($response->json("refresh_token"))) {
      throw new AudioSaladException(
        "Failed to retrieve refresh token from response."
      );
    }

    Redis::set(
      "audiosalad-refresh-token",
      $response->json("refresh_token"),
      "EX",
      72000
    ); // 20 hours

    return $response->json("access_token");
  }
}
