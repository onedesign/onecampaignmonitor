<?php
namespace Craft;

require_once __DIR__ . '/../vendor/campaignmonitor/createsend-php/csrest_lists.php';

class OneCampaignMonitor_ListsService extends OneCampaignMonitor_BaseService {

    /**
     * Ensures custom fields already exists or creates them. This also creates
     * Multi-Valued Select Many options if necessary.
     * @param  String $listId
     * @param  Array $customFields
     * @throws Exception
     */
    public function ensureCustomFieldsExist(
        $listId, $customFields, $defaultDataType='MultiSelectMany', $visibleInPreferenceCenter=false
    ){
        if (!$listId) {
            throw new Exception('List ID is required');
        }
        if (empty($customFields)) {
            throw new Exception('Custom fields are required');
        }

        $connection = new \CS_REST_Lists($listId, $this->auth());

        $result = $connection->get_custom_fields();
        $error = null;
        if (!$this->response($result, $error)) {
            throw new Exception($error);
        }

        $existingCustomFields = $result->response;

        $parsedCustomFields = $this->parseCustomFields($customFields);

        // Find all fields that don't already exist
        $fieldsToAdd = [];
        $optionsToAdd = [];
        foreach ($parsedCustomFields as $checkField) {
            $fieldExists = false;
            $optionExists = false;
            foreach ($existingCustomFields as $existingField) {
                // Campaign Monitor encloses keys in [] in this response
                if ('[' . $checkField['Key'] . ']' == $existingField->Key) {
                    $fieldExists = true;

                    // Check that the option exists for MultiSelect fields
                    if ($existingField->DataType == 'MultiSelectOne' || $existingField->DataType == 'MultiSelectMany') {
                        foreach ($existingField->FieldOptions as $option) {
                            if ($option == $checkField['Value']) {
                                $optionExists = true;
                            }
                        }
                    }
                }
            }

            if (!$fieldExists) {
                $fieldsToAdd[$checkField['Key']] = $checkField;
            }

            if (!$optionExists) {
                $optionsToAdd[$checkField['Key']][] = $checkField['Value'];
            }
        }

        // Add any fields that don't yet exist
        foreach ($fieldsToAdd as $field) {
            if (array_key_exists($field['Key'], $optionsToAdd)) {
                $options = $optionsToAdd[$field['Key']];
                unset($optionsToAdd[$field['Key']]);
            } else {
                $options = [];
            }

            $result = $connection->create_custom_field([
                'FieldName' => $field['Key'],
                'DataType' => $defaultDataType,
                'Options' =>  $options,
                'VisibleInPreferenceCenter' => $visibleInPreferenceCenter,
            ]);

            $error = null;
            if (!$this->response($result, $error)) {
                throw new Exception($error);
            }
        }

        // Add any options we haven't already added (field existed but not options)
        foreach ($optionsToAdd as $key => $options) {
            $result = $connection->update_field_options('['.$key.']', $options, true);

            $error = null;
            if (!$this->response($result, $error)) {
                throw new Exception($error);
            }
        }
    }
}
