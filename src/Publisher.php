<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher;

use craft\base\Plugin;
use Craft;
use craft\elements\Entry;
use craft\events\ElementEvent;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use goldinteractive\publisher\services\Entries;
use yii\base\Event;

/**
 * @author    Gold Interactive
 * @package   Publisher
 * @since     0.1.0
 *
 * @property \goldinteractive\publisher\services\Entries $entries
 *
 */
class Publisher extends Plugin
{
    public function init()
    {
        parent::init();

        $this->setComponents(
            [
                'entries' => Entries::class,
            ]
        );

        if (Craft::$app instanceof \craft\console\Application) {
            $this->controllerNamespace = 'goldinteractive\publisher\console\controllers';
        }

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                CraftVariable::class,
                CraftVariable::EVENT_INIT,
                function (Event $event) {
                    $variable = $event->sender;
                    $variable->set('publisherEntries', Entries::class);
                }
            );

            Event::on(
                Elements::class,
                Elements::EVENT_AFTER_SAVE_ELEMENT,
                function (ElementEvent $event) {
                    if ($event->element instanceof Entry) {
                        return self::getInstance()->entries->onSaveEntry($event->element);
                    }
                }
            );

            Craft::$app->view->hook(
                'cp.entries.edit.details',
                function (array &$context) {
                    /** @var $entry craft\elements\Entry */
                    $entry = $context['entry'];
                    $isNew = $entry->id === null;

                    if ($isNew) {
                        return null;
                    }

                    return Craft::$app->view->renderTemplate(
                        'publisher/_cp/entriesEditRightPane',
                        [
                            'permissionSuffix' => ':'.$entry->sectionId,
                            'entry'            => $entry,
                        ]
                    );
                }
            );
        }
    }
}