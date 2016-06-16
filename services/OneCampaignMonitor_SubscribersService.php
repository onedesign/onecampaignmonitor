<?php
namespace Craft;

require_once CRAFT_BASE_PATH . '../vendor/campaignmonitor/createsend-php/csrest_subscribers.php';

class OneCampaignMonitor_SubscribersService extends OneCampaignMonitor_BaseService {

    public function add($listId, $email, $name=null, $customFields=array(), $resubscribe=true) {
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
        craft()->oneCampaignMonitor_log->subscription($listId, $email);
        return true;
    }

}
