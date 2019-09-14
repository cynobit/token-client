<?php
declare(strict_types=1);
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('TokenCURL.php');

class TokenClient {

  private $baseURL = 'http://127.0.0.1/token-server/';
  private $lastStatusCode;

  function __construct(array $params=null) {
    if ($params != null) {
      $this->baseURL = $params['base_url'] ?? $this->baseURL;
    }
  }
  /**
   * [setToken description]
   * @param  int    $id    [description]
   * @param  string $token [description]
   * @return bool          [description]
   */
  public function setToken(int $id, string $token):bool {
    list($code, $response) = (new TokenCURL(TokenCURL::POST))(
      $this->baseURL.'token',
      [
        'id'    => $id,
        'token' => $token
      ]
    );
    $this->lastStatusCode = $code;
    return $code == 204;
  }
  /**
   * [getToke description]
   * @param  int         $id [description]
   * @return string|null     [description]
   */
  public function getToken(int $id):?string {
    list($code, $response) = (new TokenCURL(TokenCURL::GET))(
      $this->baseURL."token/$id"
    );
    $this->lastStatusCode = $code;
    if ($code == 200) return json_decode($response)->token;
    return null;
  }
  /**
   * [getLastStatusCode description]
   * @return int [description]
   */
  public function getLastStatusCode():int {
    return $this->lastStatusCode;
  }
}
?>
