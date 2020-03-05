<?php
declare(strict_types=1);
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenCURL
{
/**
 * [GET description]
 * @var string
 */
  const GET  = 'GET';

  /**
   * [POST description]
   * @var string
   */
  const POST = 'POST';

  /**
   * [private description]
   * @var [type]
   */
  private $method;

  /**
   * [private description]
   * @var [type]
   */
  private $userAgent;

  /**
   * [__construct description]
   * @date  2020-03-05
   * @param string     $method    [description]
   * @param string     $userAgent [description]
   */
  function __construct(string $method, string $userAgent='Token Client v0.0.1')
  {
    $this->method = $method;
    $this->userAgent = $userAgent;
  }

  /**
   * [__invoke description]
   * @date   2020-03-05
   * @param  string     $url  [description]
   * @param  [type]     $body [description]
   * @return [type]           [description]
   */
  function __invoke(string $url, array $body=null):array
  {
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
    if ($body != null) {
      //curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    // Exec.
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $response];
  }
}
