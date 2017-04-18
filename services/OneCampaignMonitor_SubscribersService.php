<?php
namespace Craft;

require_once CRAFT_BASE_PATH . '../vendor/campaignmonitor/createsend-php/csrest_subscribers.php';

class OneCampaignMonitor_SubscribersService extends OneCampaignMonitor_BaseService {

    /**
     * Adds a subscriber to a list
     * @param  $listId
     * @param  $email
     * @param  $name
     * @param  $customFields
     * @param  $resubscribe
     * @param  $merge
     * @throws Exception
     */
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

    /**
     * Determines is a subscriber exists in a list
     * @param  $listId
     * @param  $email
     * @return  Boolean
     * @throws Exception
     */
    public function exists($listId, $email) {
        if (!$listId) {
            throw new Exception('List ID is required');
        }
        if (!$email) {
            throw new Exception('Please provide a valid email address');
        }

        $connection = new \CS_REST_Subscribers($listId, $this->auth());

        $result = $connection->get($email);
            
        $error = null;
        return $this->response($result, $error);
    }

    /**
     * Updates a subscriber in a list
     * @param  $listId
     * @param  $email
     * @param  $name
     * @param  $customFields
     * @param  Boolean $resubscribe Re-activate an existing user if they have been deactivated
     * @param  Boolean $mergeMultiFields Multi-Valued Select Many fields will be merged together instead of overwritten
     * @throws Exception
     */
    public function update($listId, $email, $name=null, $customFields=array(), $resubscribe=false, $mergeMultiFields=false) {
        if (!$listId) {
            throw new Exception('List ID is required');
        }
        if (!$email) {
            throw new Exception('Please provide a valid email address');
        }

        $connection = new \CS_REST_Subscribers($listId, $this->auth());
        
        $subscriber = [
            'Resubscribe' => $resubscribe
        ];

        if (!empty($name)) {
            $subscriber['Name'] = $name;
        }

        $subscriber['CustomFields'] = $this->parseCustomFields($customFields);

        if ($mergeMultiFields) {
            $result = $connection->get($email);
            
            $error = null;
            if (!$this->response($result, $error)) {
                throw new Exception($error);
            }

            $existingSubscriber = $result->response;

            // Count the number of occurances of custom field to know 
            // which is a Multi-Valued Select Many field
            $fieldOccurances = [];
            foreach ($existingSubscriber->CustomFields as $existingField) {
                if (array_key_exists($existingField->Key, $fieldOccurances)) {
                    $fieldOccurances[$existingField->Key]++;
                } else {
                    $fieldOccurances[$existingField->Key] = 1;
                }
            }

            // For any Multi-Valued Select Many field, make sure to append the 
            // existing field values
            foreach($fieldOccurances as $key => $value) {
                if ($value > 1) {
                    foreach($existingSubscriber->CustomFields as $existingField) {
                        if ($existingField->Key == $key) {
                            $subscriber['CustomFields'][] = $existingField;
                        }
                    }
                }
            }
        } else {
            $subscriber['CustomFields'] = $parsedCustomFields;
        }
        
        $result = $connection->update($email, $subscriber);

        $error = null;
        if (!$this->response($result, $error)) {
            throw new Exception($error);
        }
    }
}
