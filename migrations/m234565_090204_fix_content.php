<?php

use yii\db\Migration;

/**
 * Class m200428_135032_fix_question_title
 */
class m234565_090204_fix_content extends Migration
{
    /**
    * {@inheritdoc}
    */
   public function safeUp()
   {
       $this->alterColumn('elements', 'content', 'longtext NULL');
   }

   /**
    * {@inheritdoc}
    */
   public function safeDown()
   {
       return false;
   }
}
