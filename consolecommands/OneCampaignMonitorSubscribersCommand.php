<?php
namespace Craft;

class OneCampaignMonitorSubscribersCommand extends BaseCommand {

    /**
     * Adds a subscriber to a list
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorsubscribers add \
     *       --listId="asdf..." \
     *       --email="test@example.com" \
     *       --name="Mr. Test" \
     *       --resubscribe=1 \
     *       --customFields='[{"Key":"City","Value":"Chicago"}]' \
     */
    public function actionAdd($listId, $email, $name=null, $customFields='[]', $resubscribe=true) {
        $customFields = $this->_decodeCustomFields($customFields);
        craft()->oneCampaignMonitor_subscribers->add($listId, $email, $name, $customFields, $resubscribe ? true : false);
    }

    /**
     * Updates a subscriber in a list
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorsubscribers update \
     *       --listId="asdf..." \
     *       --email="test@example.com" \
     *       --name="Joan Doe" \
     *       --resubscribe=0 \
     *       --customFields='[{"Key":"City","Value":"New York"}]' \
     *       --merge=1
     */
    public function actionUpdate($listId, $email, $name=null, $customFields='[]', $resubscribe=false, $merge=true) {
        $customFields = $this->_decodeCustomFields($customFields);
        craft()->oneCampaignMonitor_subscribers->update($listId, $email, $name, $customFields, $resubscribe ? true : false, $merge  ? true : false);
    }

    /**
     * Determines is an email exists in a list
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorsubscribers update \
     *       --listId="asdf..." \
     *       --email="test@example.com"
     */
    public function actionExists($listId, $email) {
        if (craft()->oneCampaignMonitor_subscribers->exists($listId, $email)) {
            OneCampaignMonitorPlugin::log('Subscriber exists in this list.', LogLevel::Info);
        } else {
            OneCampaignMonitorPlugin::log('Subscriber does not exist in the list.', LogLevel::Info);
        }
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
