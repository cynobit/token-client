<?php
declare(strict_types=1);
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('TokenCURL.php');

class TokenClient
{
  /**
   * [private description]
   * @var [type]
   */
  private $baseURL = 'http://127.0.0.1/token-server/';

  /**
   * [private description]
   * @var [type]
   */
  private $lastResponseCode;

  /**
   * [private description]
   * @var [type]
   */
  private $lastResponse;

  /**
   * [__construct description]
   * @date  2020-03-05
   * @param [type]     $params [description]
   */
  function __construct(?array $params=null)
  {
    if ($params) {
      $this->baseURL = $params['base_url'] ?? $this->baseURL;
    }
  }

  /**
   * [setToken description]
   * @param  int    $id    [description]
   * @param  string $token [description]
   * @return bool          [description]
   */
  public function setToken(int $id, string $access_token, string $refresh_token,
  int $exp_at, string $provider):bool
  {
    list($code, $response) = (new TokenCURL(TokenCURL::POST))(
      $this->baseURL.'token',
      [
        'id'            => $id,
        'access_token'  => $access_token,
        'refresh_token' => $refresh_token,
        'exp_at'        => $exp_at,
        'provider'      => $provider
      ]
    );

    $this->lastResponseCode = $code;
    $this->lastResponse = $response;

    return $code == 204;
  }

  /**
   * [getToken description]
   * @param  int    $id       [description]
   * @param  string $provider [description]
   * @return [type]           [description]
   */
  public function getToken(int $id, string $provider):?string
  {
    list($code, $response) = (new TokenCURL(TokenCURL::GET))(
      $this->baseURL."token/$id/$provider"
    );

    $this->lastResponseCode = $code;
    $this->lastResponse = $response;

    if ($code == 200) return json_decode($response)->access_token;

    return null;
  }

  /**
   * [getLastResponseCode description]
   * @return int [description]
   */
  public function getLastResponseCode():int {
    return $this->lastResponseCode;
  }

  /**
   * [getLastResponse description]
   * @date   2020-03-06
   * @return string     [description]
   */
  public function getLastResponse():string
  {
    return $this->lastResponse;
  }
}
