<?php
/**
 * Class m180222_102301_forum_index
 */
class m180222_102301_forum_index extends \yii\db\Migration {
    // Public Variables

    // Private Variables

    private $prefix;

    public function init() {

        // Table prefix
        $this->prefix   = Yii::$app->migration->cmgPrefix;
    }
    
    public function up() {

        $this->upPrimary();
    }
    
    private function upPrimary() {

        // Topic
        $this->createIndex( 'idx_' . $this->prefix . 'topic_name', $this->prefix . 'forum_topic', 'name' );
        $this->createIndex( 'idx_' . $this->prefix . 'topic_slug', $this->prefix . 'forum_topic', 'slug' );
    }
    
    public function down() {

        $this->downPrimary();
    }
    
    private function downPrimary() {

        // Topic
        $this->dropIndex( 'idx_' . $this->prefix . 'topic_name', $this->prefix . 'forum_topic' );
        $this->dropIndex( 'idx_' . $this->prefix . 'topic_slug', $this->prefix . 'forum_topic' );
    }
}
