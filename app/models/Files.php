<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $title_1
 * @property string $title_2
 * @property string $filename
 * @property string $format
 * @property string $table_name
 * @property string $record_id
 * @property integer $creation_time
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_1', 'title_2', 'filename', 'format', 'table_name', 'record_id', 'creation_time'], 'required'],
            [['creation_time'], 'integer'],
            [['title_1', 'title_2', 'filename', 'format', 'table_name', 'record_id'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title_1' => Yii::t('app', 'Подпись рус'),
            'title_2' => Yii::t('app', 'Підпис укр'),
            'filename' => Yii::t('app', 'Назва файлу'),
            'format' => Yii::t('app', 'Файл для завантаження'),
            'table_name' => Yii::t('app', 'Таблиця'),
            'record_id' => Yii::t('app', 'ID from table'),
            'creation_time' => Yii::t('app', 'Date of creation'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\Files the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\Files(get_called_class());
    }
    
    public function getTitle(){
        
        if(Lang::getCurrentId() == 1)
			return $this->title_1;
		else
			return $this->title_2 ? $this->title_2 : $this->title_1;
		
    }
    public function getClass(){
        
        if($this->format == 'doc' || $this->format == 'docx')
			return 'icon-file-word';
        elseif ($this->format == 'xls' || $this->format == 'xlsx')
            return 'icon-file-excel';
        else 
            return 'icon-file-pdf';
    }
    public function getUrl(){
        
            return "/userfiles/{$this->format}/{$this->filename}";
    }
}
