<?php
namespace Craft;

class OneCampaignMonitorSubscribersCommand extends BaseCommand {

    /**
     * Adds a user to a list
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorsubscribers add \
     *       --listId="asdf..." \
     *       --email="test@example.com" \
     *       --name="Mr. Test" \
     *       --resubscribe=1 \
     *       --customFields='[{"Key":"Subscriptions","Value":"FN-SUB36-Renewal"}]' \
     */
    public function actionAdd($listId, $email, $name=null, $customFields='[]', $resubscribe=true) {
        $customFields = $this->_decodeCustomFields($customFields);
        craft()->oneCampaignMonitor_subscribers->add($listId, $email, $name, $customFields, $resubscribe ? true : false);
    }

    /**
     * Looks through all orders and adds users to User Groups based on their subscription items
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorsubscribers update \
     *       --listId="asdf..." \
     *       --email="test@example.com" \
     *       --name="Joan Doe" \
     *       --resubscribe=0 \
     *       --customFields='[{"Key":"Subscriptions","Value":"FN-SUB36-Renewal"}]' \
     *       --merge=1
     */
    public function actionUpdate($listId, $email, $name=null, $customFields='[]', $resubscribe=false, $merge=true) {
        $customFields = $this->_decodeCustomFields($customFields);
        craft()->oneCampaignMonitor_subscribers->update($listId, $email, $name, $customFields, $resubscribe ? true : false, $merge  ? true : false);
    }

    /**
     * Decodes the customFields input option
     * @param  String $customFields JSON array of key/value objects
     * @return Array
     */
    private function _decodeCustomFields($customFields) {
        $decoded = json_decode($customFields, true);

        if (!is_array($decoded)) {
            throw new Exception('customFields must be specified as a JSON array of key/value objects');
        }

        return $decoded;
    }

}
