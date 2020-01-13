<?php
namespace Craft;

class OneCampaignMonitor_RecaptchaService extends BaseApplicationComponent {
  public function verify($response) {
    $secret = craft()->plugins->getPlugin('OneCampaignMonitor')->getSettings()->google_recaptcha_secret_key

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $fields = array('secret' => $secret, 'response' => $response);
    $fields_string = http_build_query($fields);

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($result, true);

    return $json;
  }

}
