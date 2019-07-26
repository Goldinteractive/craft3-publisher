<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\records;

use craft\base\Element;
use craft\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

/**
 * Class EntryPublish
 *
 * @package goldinteractive\publisher\records
 *
 * @property int       $id
 * @property int       $sourceId
 * @property int       $publishDraftId
 * @property bool      $expire
 * @property \DateTime $publishAt
 */
class EntryPublish extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%entrypublishes}}';
    }

    /**
     * Returns element
     *
     * @return ActiveQueryInterface
     */
    public function getElement(): ActiveQueryInterface
    {
        return $this->hasOne(Element::class, ['id' => 'id']);
    }

    /**
     * Returns the element.
     *
     * @return ActiveQueryInterface
     */
    public function getEntry(): ActiveQueryInterface
    {
        return $this->hasOne(Element::class, ['id' => 'sourceId']);
    }

    /**
     * Returns the entry draft.
     *
     * @return ActiveQueryInterface
     */
    public function getDraft(): ActiveQueryInterface
    {
        return $this->hasOne(Element::class, ['id' => 'publishDraftId']);
    }
}