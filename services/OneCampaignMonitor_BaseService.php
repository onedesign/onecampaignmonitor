<?php
namespace Craft;

class OneCampaignMonitor_BaseService extends BaseApplicationComponent {
    private $_auth;

    protected function auth() {
        if (is_null($this->_auth)) {
            if ($key = craft()->plugins->getPlugin('OneCampaignMonitor')->getSettings()->campaignmonitor_api_key) {
                $this->_auth = ['api_key' => $key];
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
            $error = $result->response->Message;
            OneCampaignMonitorPlugin::log($error, LogLevel::Error);
            return false;
        }
    }
}
