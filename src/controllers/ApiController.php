<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\controllers;

use craft\web\Controller;
use goldinteractive\publisher\Publisher;

/**
 * Class ApiController
 *
 * @package goldinteractive\publisher\controllers
 */
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    protected $allowAnonymous = ['publish'];

    /**
     * Publishes or expires all due entries.
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionPublish()
    {
        $result = Publisher::getInstance()->entries->publishDueEntries();

        return $this->asJson($result);
    }
}
