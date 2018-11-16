<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\elements\db;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;

class EntryPublishQuery extends ElementQuery
{
    /**
     * @var int|array|null The entry ID(s) to query for.
     */
    public $entryId;

    /**
     * @var int|array|null The draft ID(s) to query for.
     */
    public $draftId;

    /**
     * @var \DateTime|null The DateTime to query for.
     */
    public $publishAt;

    /**
     * @var boolean|null
     */
    public $expire;

    /**
     * Filters the query results based on the entry ID.
     *
     * @param int|array|null $value The entry ID(s).
     * @return $this
     */
    public function entryId(int $value)
    {
        $this->entryId = $value;

        return $this;
    }

    /**
     * Filters the query results based on the draft ID.
     *
     * @param int|array|null $value The draft ID(s).
     * @return $this
     */
    public function draftId(int $value)
    {
        $this->draftId = $value;

        return $this;
    }

    /**
     * Filters the query results based on the DateTime.
     *
     * @param \DateTime|null $value The DateTime.
     * @return $this
     */
    public function publishAt(\DateTime $value)
    {
        $this->publishAt = $value;

        return $this;
    }

    /**
     * Filters the query results based on the expire value.
     *
     * @param bool|null $value The expire value.
     * @return $this
     */
    public function expire(bool $value)
    {
        $this->expire = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('entrypublishes');

        $this->query->select(
            [
                'entrypublishes.*',
            ]
        );

        if ($this->entryId !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.entryId', $this->entryId));
        }

        if ($this->draftId !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.draftId', $this->draftId));
        }

        if ($this->publishAt !== null) {
            $this->subQuery->andWhere(Db::parseDateParam('entrypublishes.publishAt', $this->publishAt, '<='));
        }

        if ($this->expire !== null) {
            $this->subQuery->andWhere(Db::parseParam('entrypublishes.expire', $this->expire));
        }

        return parent::beforePrepare();
    }
}
