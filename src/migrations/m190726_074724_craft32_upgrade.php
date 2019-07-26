<?php

namespace goldinteractive\publisher\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Table;
use craft\helpers\MigrationHelper;

/**
 * m190726_074724_craft32_upgrade migration.
 */
class m190726_074724_craft32_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        MigrationHelper::dropForeignKey('{{%entrypublishes}}', ['draftId'], $this);

        $this->renameColumn('{{%entrypublishes}}', 'entryId', 'sourceId');
        $this->renameColumn('{{%entrypublishes}}', 'draftId', 'publishDraftId');

        $this->addForeignKey(
            null,
            '{{%entrypublishes}}',
            ['publishDraftId'],
            '{{%drafts}}',
            ['id'],
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190726_074724_craft32_upgrade cannot be reverted.\n";

        return false;
    }
}
