<?php
namespace Craft;

class Onecampaignmonitor_SubscribersController extends BaseController
{
    protected $allowAnonymous = true;

    public function actionAdd() {
        $this->requirePostRequest();

        //required fields
        $listId = craft()->request->getParam('listId');
        $email  = craft()->request->getParam('email');

        //optional fields with defaults
        $name   = craft()->request->getParam('name') ?: '';
        $customFields = craft()->request->getParam('customFields') ?: array();
        $resubscribe = craft()->request->getParam('resubscribe') ?: true;
        $consenttotrack = craft()->request->getParam('consenttotrack') ?: 'Unchanged';

        $error = null;
        try {
            craft()->oneCampaignMonitor_subscribers->add($listId, $email, $name, $customFields, $resubscribe, $consenttotrack);
        } catch (Exception $e) {
            OneCampaignMonitorPlugin::log($e->getMessage(), LogLevel::Error);
            $error = $e->getMessage();
        }

        //return json for ajax requests, redirect to posted url otherwise
        if (craft()->request->isAjaxRequest()) {
            $this->returnJson(['success' => is_null($error),
                               'error' => $error]);
        } else {
            craft()->userSession->setFlash('oneCampaignMonitor_addSubscriberSuccess', is_null($error));
            craft()->userSession->setFlash('oneCampaignMonitor_addSubscriberMessage', Craft::t($error ?: 'Success!'));
            $this->redirectToPostedUrl();
        }
    }
}
