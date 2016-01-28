<?php
namespace Craft;

class OneCampaignMonitor_LogService extends BaseApplicationComponent {
    const SUBSCRIPTION_KEY_PREFIX = 'oneCampaignMonitor_subscription__';

    public function subscription($listId) {
        craft()->httpSession->add(SUBSCRIPTION_KEY_PREFIX . $listId, true);
    }

    public function hasSubscribed($listId) {
        return (bool)craft()->httpSession->get(SUBSCRIPTION_KEY_PREFIX . $listId);
    }

}
