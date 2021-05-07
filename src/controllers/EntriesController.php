<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\controllers;

use Craft;
use craft\elements\Entry;
use craft\errors\ElementNotFoundException;
use craft\helpers\DateTimeHelper;
use craft\web\Controller;
use goldinteractive\publisher\elements\EntryPublish;
use goldinteractive\publisher\Publisher;
use yii\web\NotFoundHttpException;

/**
 * Class EntriesController
 *
 * @package goldinteractive\publisher\controllers
 */
class EntriesController extends Controller
{
    /**
     * Saves an EntryPublish.
     *
     * @throws ElementNotFoundException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionSave()
    {
        $this->requirePostRequest();

        $draftId = Craft::$app->request->post('publisher_draftId');
        $publishAt = Craft::$app->request->post('publisher_publishAt');
        $siteId = Craft::$app->request->post('publisher_sourceSiteId');

        $draft = Entry::find()
            ->draftId($draftId)
            ->siteId($siteId)
            ->one();

        if ($draft === null) {
            throw new \Exception('Invalid entry draft ID: '.$draftId);
        }

        $entry = Craft::$app->entries->getEntryById($draft->sourceId, $siteId);

        if ($entry === null) {
            throw new ElementNotFoundException("No element exists with the ID '{$draft->sourceId}'");
        }

        if ($draft->enabled) {
            $this->requirePermission('publishEntries:'.$entry->section->uid);
        }

        if ($publishAt !== null) {
            $publishAt = DateTimeHelper::toDateTime($publishAt, true);
        }

        $model = new EntryPublish();
        $model->sourceId = $entry->id;
        $model->publishDraftId = $draft->draftId;
        $model->publishAt = $publishAt;
        $model->sourceSiteId = $siteId;

        if (!Publisher::getInstance()->entries->saveEntryPublish($model)) {
            Craft::$app->getUrlManager()->setRouteParams(
                [
                    'publisherEntry' => $model,
                ]
            );
        }
    }


    /**
     * Deletes the EntryPublish.
     *
     * @return bool
     * @throws \Throwable
     */
    public function actionDelete()
    {
        $entriesService = Publisher::getInstance()->entries;
        $publishEntryId = Craft::$app->request->getQueryParam('sourceId');

        if ($publishEntryId === null) {
            throw new NotFoundHttpException('EntryPublish not found');
        }

        $entryPublish = $entriesService->getEntryPublishById($publishEntryId);

        if ($entryPublish !== null) {
            $entry = $entryPublish->getEntry();

            $entriesService->deleteEntryPublish($publishEntryId);
            $this->redirect($entry->getCpEditUrl());

            return true;
        }

        throw new NotFoundHttpException('EntryPublish not found');
    }
}
