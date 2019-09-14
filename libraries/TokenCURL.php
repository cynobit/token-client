<?php
declare(strict_types=1);
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenCURL {

  const GET  = 'GET';
  const POST = 'POST';

  private $method;
  private $userAgent;

  function __construct(string $method, string $userAgent='Token Client v0.0.1') {
    $this->method = $method;
    $this->userAgent = $userAgent;
  }

  function __invoke(string $url, array $body=null):array {
    if ($body != null) $body = http_build_query($body);
    $ch = curl_init($url);

    // Defaults.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    if (ENVIRONMENT == 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    // Header.
    $header = [
      'Content-Type: application/x-www-form-urlencoded'
    ];
    if ($body != null) $header[] = 'Content-Length: '.strlen($body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    // Request Method and Body.
    if ($this->method == self::POST) {
      curl_setopt($ch, CURLOPT_POST, true);
      if ($body != null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      }
    }
    // Exec.
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $response];
  }
}
