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


        //validate and send
        if ($listId && $email) {
            if (!craft()->oneCampaignMonitor_subscribers->add($listId, $email, $name, $customFields, $resubscribe)) {
                throw new HttpException(400);
            }
        } else {
            throw new HttpException(400);
        }

        //success! redirect to posted redirect url
        $this->redirectToPostedUrl();
    }
}
