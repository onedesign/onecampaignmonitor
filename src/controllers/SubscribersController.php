<?php
/**
 * one-campaign-monitor plugin for Craft CMS 3.x
 *
 * Craft CMS Plugin for Campaign Monitor Integration
 *
 * @link      https://github.com/onedesign
 * @copyright Copyright (c) 2020 Michael Ramuta
 */

namespace onedesign\onecampaignmonitor\controllers;

use onedesign\onecampaignmonitor\Onecampaignmonitor;

use Craft;
use craft\web\Controller;
use Exception;

/**
 * @author    Michael Ramuta
 * @package   Onecampaignmonitor
 * @since     1.0.0
 */
class SubscribersController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['add'];

    // Public Methods
    // =========================================================================

    public function actionAdd() {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        // Verify the recaptcha response before creating the user
        if (strlen(getenv('GOOGLE_RECAPTCHA_SECRET_KEY')) > 0) {
            $response = Craft::$app->request->post('g-recaptcha-response');
            $result = Onecampaignmonitor::$plugin->recaptcha->verify($response);
            if (!$result['success']) {
                return $this->asErrorJson('Recaptcha Not Successful');
            }
        }

        //required fields
        $listId = Craft::$app->request->getRequiredParam('listId');
        $email = Craft::$app->request->getRequiredParam('email');

        //optional fields with defaults
        $name = Craft::$app->request->post('name', '');
        $customFields = Craft::$app->request->post('customFields', []);
        $resubscribe = Craft::$app->request->post('resubscribe', true);

        $error = null;
        try {
            Onecampaignmonitor::$plugin->subscribers->add($listId, $email, $name, $customFields, $resubscribe);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if (is_null($error)) {
            return $this->asErrorJson($error);
        } else {
            return $this->asJson(['success' => true]);
        }
        // TODO: Handle redirect if not json
    }
}
