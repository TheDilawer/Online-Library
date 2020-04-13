<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../JWT/BeforeValidException.php';
require_once '../../JWT/ExpiredException.php';
require_once '../../JWT/SignatureInvalidException.php';
require_once '../../JWT/JWT.php';

use \Firebase\JWT\JWT;


class Token
{
  public $secret_key;
  public $issuer_claim;
  public $audience_claim;
  public $issuedat_claim;
  public $notbefore_claim;
  public $expire_claim;
  public $tokenContent;


  public function __construct($generatedSecretKey)
  {
    $this->secret_key=$generatedSecretKey;
    $this->issuer_claim = "Web_Server"; // this can be the servername
    $this->audience_claim = "www.lamabilisim.com/library";
    $this->issuedat_claim = time(); // issued at
    $this->notbefore_claim = $this->issuedat_claim; //not before in seconds
    $this->expire_claim = $this->issuedat_claim + 60*15; // expire time in seconds
  }

  public function generateJwtToken($claimerId,$claimerUsername,$claimerRole)
  {


    $this->tokenContent = array(
      "iss" => $this->issuer_claim,
      "aud" => $this->audience_claim,
      "iat" => $this->issuedat_claim,
      "nbf" => $this->notbefore_claim,
      "exp" => $this->expire_claim,
      "data" => array(
        "id" => $claimerId,
        "username" => $claimerUsername,
        "role" => $claimerRole
      ));

    $jwt = JWT::encode($this->tokenContent, $this->secret_key );

    return $jwt;
  }




}
