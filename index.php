<?
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once __DIR__.'/vendor/autoload.php';
use Namshi\JOSE\SimpleJWS;


$private_key                 = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
your private key goes here
-----END RSA PRIVATE KEY-----
EOF;

$key_id = 'layer:///keys/48d60bea-945a-11e6-3564-4ffa04334881';
$provider_id = 'layer:///providers/f4029942-8b8c-18e6-8a28-c3b71f1e2a1c';
$user_id = 820211;
$nonce = 'nonce';

$jws = new SimpleJWS(array(
    // String - Expresses a MIME Type of application/JWT
    'typ' => 'JWT',
    // String - Expresses the type of algorithm used to sign the token;
    // must be RS256
    'alg' => 'RS256',
    // String - Express a Content Type of Layer External Identity Token,
    // version 1
    'cty' => 'layer-eit;v=1',
    // String - Private Key associated with "layer.pem", found in the
    // Layer Dashboard
    'kid' => $key_id
));
$jws->setPayload(array(
    // String - The Provider ID found in the Layer Dashboard
    'iss' => $provider_id,
    // String - Provider's internal ID for the authenticating user
    'prn' => $user_id,
    // Integer - Time of Token Issuance in RFC 3339 seconds
    'iat' => round(microtime(true) * 1000),
    // Integer - Token Expiration in RFC 3339 seconds; set to 2 minutes
    'exp' => round(microtime(true) * 1000) + 120,
    # The nonce obtained via the Layer client SDK.
    'nce' => $nonce
));

$private_key = openssl_pkey_get_private($private_key);
$jws->sign($private_key);
$identityToken = $jws->getTokenString();

var_dump($identityToken);



?>