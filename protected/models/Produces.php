<?php

/**
 * This is the model class for table "produces".
 *
 * The followings are the available columns in table 'produces':
 * @property integer $item_manufacturer_id
 * @property integer $item_distributor_id
 * @property integer $production_item_id
 *
 * The followings are the available model relations:
 * @property Distributor $itemDistributor
 * @property Manufacturer $itemManufacturer
 * @property Item $productionItem
 */
class Produces extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Produces the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'produces';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('production_item_id', 'required'),
			array('item_manufacturer_id, item_distributor_id, production_item_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_manufacturer_id, item_distributor_id, production_item_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'itemDistributor' => array(self::BELONGS_TO, 'Distributor', 'item_distributor_id'),
			'itemManufacturer' => array(self::BELONGS_TO, 'Manufacturer', 'item_manufacturer_id'),
			'productionItem' => array(self::BELONGS_TO, 'Item', 'production_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'item_manufacturer_id' => 'Item Manufacturer',
			'item_distributor_id' => 'Item Distributor',
			'production_item_id' => 'Production Item',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('item_manufacturer_id',$this->item_manufacturer_id);
		$criteria->compare('item_distributor_id',$this->item_distributor_id);
		$criteria->compare('production_item_id',$this->production_item_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}