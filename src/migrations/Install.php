<?php
/**
 * @link      https://www.goldinteractive.ch
 * @copyright Copyright (c) 2018 Gold Interactive
 * @author Christian Ruhstaller
 * @license MIT
 */

namespace goldinteractive\publisher\migrations;

use craft\db\Migration;

/**
 * Class Install
 *
 * @package goldinteractive\publisher\migrations
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $hasEntryPublishTable = $this->db->tableExists('{{%entrypublishes}}');

        // Create tables
        if (!$hasEntryPublishTable) {
            $this->createTable(
                '{{%entrypublishes}}',
                [
                    'id'          => $this->integer()->notNull(),
                    'entryId'     => $this->integer()->notNull(),
                    'draftId'     => $this->integer(),
                    'publishAt'   => $this->dateTime()->notNull(),
                    'expire'      => $this->boolean()->defaultValue(false),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid'         => $this->uid(),
                    'PRIMARY KEY([[id]])',
                ]
            );
        }
        // Add foreign keys
        if (!$hasEntryPublishTable) {
            $this->addForeignKey(null, '{{%entrypublishes}}', ['id'], '{{%elements}}', ['id'], 'CASCADE', null);
            $this->addForeignKey(null, '{{%entrypublishes}}', ['entryId'], '{{%elements}}', ['id'], 'CASCADE', null);
            $this->addForeignKey(
                null,
                '{{%entrypublishes}}',
                ['draftId'],
                '{{%entrydrafts}}',
                ['id'],
                'CASCADE',
                'CASCADE'
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%entrypublishes}}');

        return true;
    }
}
