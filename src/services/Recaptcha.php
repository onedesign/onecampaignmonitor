<?php
/**
 * one-campaign-monitor plugin for Craft CMS 3.x
 *
 * Craft CMS Plugin for Campaign Monitor Integration
 *
 * @link      https://github.com/onedesign
 * @copyright Copyright (c) 2020 Michael Ramuta
 */

namespace onedesign\onecampaignmonitor\services;

use onedesign\onecampaignmonitor\Onecampaignmonitor;

use Craft;
use craft\base\Component;

/**
 * @author    Michael Ramuta
 * @package   Onecampaignmonitor
 * @since     1.0.0
 */
class Recaptcha extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function verify($response) {
        $secret = getenv('GOOGLE_RECAPTCHA_SECRET_KEY');

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
