<?php
namespace Craft;

class OneCampaignMonitor_LogService extends BaseApplicationComponent {
    const SUBSCRIPTION_KEY_PREFIX = 'oneCampaignMonitor_subscription__';

    public function subscription($listId, $email) {
        craft()->httpSession->add(self::SUBSCRIPTION_KEY_PREFIX . $listId, $email);
    }

    public function hasSubscribed($listId) {
        return craft()->httpSession->get(self::SUBSCRIPTION_KEY_PREFIX . $listId);
    }

}
