<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\console\controllers;

use goldinteractive\publisher\Publisher;
use yii\console\Controller;

class PublishController extends Controller
{
    /**
     * Publishes the due entries.
     *
     * @throws \Throwable
     */
    public function actionIndex()
    {
        Publisher::getInstance()->entries->publishDueEntries();

        return;
    }
}
