<?php

require_once(__DIR__ . '/vendor/autoload.php');

define('API_KEY', '9e0719a8'); // use your fuckin key
define('API_SECRET', 'q7g2VwgXcCKHyeRa'); // also ur fuckin secret

define('SMS_FROM', 'TLNET');
define('SMS_BODY', file_get_contents(__DIR__ . '/resource/message.txt'));

$client = new Nexmo\Client(new Nexmo\Client\Credentials\Basic(API_KEY, API_SECRET));
if (isset($argv[1]))
{
  if (!file_exists(__DIR__ . '/resource/' . $argv[1])) {
    die('list not found.');
  }
  if (!file_exists(__DIR__ . '/resource/message.txt')) {
    die('message.txt not found.');
  }

  $list   = explode("\n", str_replace("\r", "", file_get_contents(__DIR__ . '/resource/' . $argv[1])));
  foreach ($list as $key => $number) {
    if (empty($number)) { continue; }
    try {
      $message = $client->message()->send([
        'to'    => $number,
        'from'  => SMS_FROM,
        'text'  => SMS_BODY
      ]);

      echo "Sent message to " . $message['to'] . ". Balance is now " . $message['remaining-balance'];

    } catch (\Exception $e) {
      echo $number . ' - FAIL';
    }

    echo PHP_EOL;
  }
} else { die(PHP_EOL.'command: php script.php list.txt'.PHP_EOL.'(put list.txt on /resource path)'.PHP_EOL.PHP_EOL); }
