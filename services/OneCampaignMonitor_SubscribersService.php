<?php
namespace Craft;

require_once CRAFT_BASE_PATH . '/vendor/campaignmonitor/createsend-php/csrest_subscribers.php';

class OneCampaignMonitor_SubscribersService extends OneCampaignMonitor_BaseService {

    public function add($list_id, $email, $name=null, $customFields=array(), $resubscribe=true) {
        $connection = new \CS_REST_Subscribers($list_id, $this->auth());
        $result = $connection->add([
            'EmailAddress' => $email,
            'Name' => $name,
            'CustomFields' => $this->parseCustomFields($customFields),
            'Resubscribe' => $resubscribe
        ]);

        return $this->response($result, 'Failed to subscribe ' . $email . ' to list ' . $list_id);
    }

}
