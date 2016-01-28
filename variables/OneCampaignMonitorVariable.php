<?php
namespace Craft;

class OneCampaignMonitorVariable {
    public function subscribe($list_id, $email, $name=null, $customFields=array(), $resubscribe=true) {
        return craft()->oneCampaignMonitor_subscribers->add($list_id, $email, $name, $customFields, $resubscribe);
    }

    public function hasSubscribed($listId) {
        return craft()->oneCampaignMonitor_log->hasSubscribed($listId);
    }
}
