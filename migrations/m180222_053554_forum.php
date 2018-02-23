<?php
class m180222_053554_forum extends \yii\db\Migration {
    // Public Variables

    public $fk;
    public $options;

    // Private Variables

    private $prefix;
    
    public function init() {

        // Table prefix
        $this->prefix	= Yii::$app->migration->cmgPrefix;

        // Get the values via config
        $this->fk	= Yii::$app->migration->isFk();
        $this->options	= Yii::$app->migration->getTableOptions();

        // Default collation
        if( $this->db->driverName === 'mysql' ) {

            $this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
    }
    
    public function up() {

        // topic
        $this->upTopic();
        
        // Reply
        $this->upReply();

        if( $this->fk ) {

            $this->generateForeignKeys();
        }
    }
    
    private function upTopic() {

        $this->createTable( $this->prefix . 'forum_topic', [
                'id' => $this->bigPrimaryKey( 20 ),
                'siteId' => $this->bigInteger( 20 )->notNull(),
                'createdBy' => $this->bigInteger( 20 )->notNull(),
                'modifiedBy' => $this->bigInteger( 20 ),                
                'status' => $this->smallInteger( 6 ),
                'name' => $this->string( Yii::$app->core->largeText )->notNull(),
                'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),  
                'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
                'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),                
                'createdAt' => $this->dateTime()->notNull(),
                'modifiedAt' => $this->dateTime(),
                'content' => $this->text(),
                'data' => $this->text()
        ], $this->options );
        
        // Indexes for forum_topic
        $this->createIndex( 'idx_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic', 'createdBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic', 'modifiedBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic', 'siteId' );        
    }
    
    private function upReply() {
        
        $this->createTable( $this->prefix . 'forum_reply', [
                'id' => $this->bigPrimaryKey( 20 ),
                'baseId' => $this->bigInteger( 20 ),
                'createdBy' => $this->bigInteger( 20 )->notNull(),
                'modifiedBy' => $this->bigInteger( 20 ),
                'topicId' => $this->bigInteger( 20 )->notNull(),
                'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
                'agent' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
                'fragment' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
                'createdAt' => $this->dateTime()->notNull(),
                'modifiedAt' => $this->dateTime(),
                'content' => $this->text(),
                'data' => $this->text()
        ], $this->options );
        
        // Indexes for forum_reply
        $this->createIndex( 'idx_' . $this->prefix . 'reply_creator', $this->prefix . 'forum_reply', 'createdBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'reply_modifier', $this->prefix . 'forum_reply', 'modifiedBy' );
        $this->createIndex( 'idx_' . $this->prefix . 'reply_topic', $this->prefix . 'forum_reply', 'topicId' );        
    }
    
    private function generateForeignKeys() {

        // Topic
        $this->addForeignKey( 'fk_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic', 'modifiedBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
        
        // Reply
        $this->addForeignKey( 'fk_' . $this->prefix . 'reply_creator', $this->prefix . 'forum_reply', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'reply_modifier', $this->prefix . 'forum_reply', 'modifiedBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'reply_topic', $this->prefix . 'forum_reply', 'topicId', $this->prefix . 'forum_topic', 'id', 'RESTRICT' );
    }
    
    public function down() {

        if( $this->fk ) {

            $this->dropForeignKeys();
        }

        $this->dropTable( $this->prefix . 'forum_topic' );
        $this->dropTable( $this->prefix . 'forum_reply' );
    }
    
    private function dropForeignKeys() {

        // Topic
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_creator', $this->prefix . 'forum_topic' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_modifier', $this->prefix . 'forum_topic' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'topic_site', $this->prefix . 'forum_topic' );
        
        // Reply
        $this->dropForeignKey( 'fk_' . $this->prefix . 'reply_creator', $this->prefix . 'forum_reply' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'reply_modifier', $this->prefix . 'forum_reply' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'reply_topic', $this->prefix . 'forum_reply' );
    }
}
