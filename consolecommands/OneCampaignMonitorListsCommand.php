<?php
namespace Craft;

class OneCampaignMonitorListsCommand extends BaseCommand {

    /**
     * Ensures an array of custom fields exists for a given list
     * Usage:
     *   php ./craft/app/etc/console/yiic onecampaignmonitorlists ensureCustomFieldsExist \
     *       --listId="asdf..." \
     *       --customFields='[{"Key":"City","Value":"Chicago"}]'
     */
    public function actionEnsureCustomFieldsExist($listId, $customFields='[]') {
        $customFields = $this->_decodeCustomFields($customFields);
        craft()->oneCampaignMonitor_lists->ensureCustomFieldsExist($listId, $customFields);
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
