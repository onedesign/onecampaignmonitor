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
use Exception;
require_once CRAFT_VENDOR_PATH . '/campaignmonitor/createsend-php/csrest_subscribers.php';

/**
 * @author    Michael Ramuta
 * @package   Onecampaignmonitor
 * @since     1.0.0
 */
class Subscribers extends Component
{
    private $_auth;

    // Public Methods
    // =========================================================================

    /**
     * Adds a subscriber to a list
     * @param  $listId
     * @param  $email
     * @param  $name
     * @param  $customFields
     * @param  $resubscribe
     * @param  $merge
     * @throws Exception
     */
    public function add($listId, $email, $name=null, $customFields=[], $resubscribe=true) {
        if (!$listId) {
            throw new Exception('List ID is required');
        }
        if (!$email) {
            throw new Exception('Please provide a valid email address');
        }

        $connection = new \CS_REST_Subscribers($listId, $this->auth());
        $result = $connection->add([
            'EmailAddress' => $email,
            'Name' => $name,
            'CustomFields' => $this->parseCustomFields($customFields),
            'Resubscribe' => $resubscribe
        ]);

        $error = null;
        if (!$this->response($result, $error)) {
            throw new Exception($error);
        }
        // TODO: add log
        // craft()->oneCampaignMonitor_log->subscription($listId, $email);
        return true;
    }

    protected function auth() {
        if (is_null($this->_auth)) {
            if (strlen(getenv('CAMPAIGN_MONITOR_API_KEY')) > 0) {
                $this->_auth = ['api_key' => getenv('CAMPAIGN_MONITOR_API_KEY')];
            } else {
                throw new Exception('Must authenticate with Campaign Monitor');
            }
        }
        return $this->_auth;
    }

    protected function parseCustomFields($fields) {
        $data = array();
        foreach ($fields as $key => $value) {
            if (is_array($value) && array_key_exists('Key', $value) && array_key_exists('Value', $value) ) {
                $data[] = $value;
            } else {
                $data[] = ['Key' => $key, 'Value' => $value];
            }
        }
        return $data;
    }

    protected function response($result, &$error) {
        if ($result->was_successful()) {
            $error = null;
            return true;
        } else {
            // TODO: logging
            // $error = $result->response->Message;
            // OneCampaignMonitorPlugin::log($error, LogLevel::Error);
            return false;
        }
    }
}
