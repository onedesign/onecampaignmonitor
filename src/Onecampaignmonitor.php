<?php
/**
 * one-campaign-monitor plugin for Craft CMS 3.x
 *
 * Craft CMS Plugin for Campaign Monitor Integration
 *
 * @link      https://github.com/onedesign
 * @copyright Copyright (c) 2020 Michael Ramuta
 */

namespace onedesign\onecampaignmonitor;

use onedesign\onecampaignmonitor\services\Recaptcha as RecaptchaService;
use onedesign\onecampaignmonitor\services\Base as BaseService;
use onedesign\onecampaignmonitor\services\Subscribers as SubscribersService;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class Onecampaignmonitor
 *
 * @author    Michael Ramuta
 * @package   Onecampaignmonitor
 * @since     1.0.0
 *
 * @property  RecaptchaService $recaptcha
 * @property  BaseService $base
 * @property  SubscribersService $subscribers
 */
class Onecampaignmonitor extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Onecampaignmonitor
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::info(
            Craft::t(
                'one-campaign-monitor',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
