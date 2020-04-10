<?php
namespace Craft;

class OneCampaignMonitorVariable {
    public function subscribe($list_id, $email, $name=null, $customFields=array(), $resubscribe=true, $consenttotrack='Unchanged') {
        return craft()->oneCampaignMonitor_subscribers->add($list_id, $email, $name, $customFields, $resubscribe, $consenttotrack);
    }

    public function hasSubscribed($listId) {
        return craft()->oneCampaignMonitor_log->hasSubscribed($listId);
    }
}
