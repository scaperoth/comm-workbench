<?php

/**
 * This is the model class for table "equiment".
 *
 * The followings are the available columns in table 'equiment':
 * @property integer $equip_id
 * @property integer $type_id
 * @property string $name
 * @property double $price_per_unit
 * @property string $quantity
 *
 * The followings are the available model relations:
 * @property EquipmentType $type
 * @property Store[] $stores
 */
class Equipment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Equipment the static model class
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
		return 'equiment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('type_id', 'numerical', 'integerOnly'=>true),
			array('price_per_unit', 'numerical'),
			array('quantity', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('equip_id, type_id, name, price_per_unit, quantity', 'safe', 'on'=>'search'),
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
			'type' => array(self::BELONGS_TO, 'EquipmentType', 'type_id'),
			'stores' => array(self::MANY_MANY, 'Store', 'store_equipment(store_equip_id, equipment_store_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'equip_id' => 'Equip',
			'type_id' => 'Type',
			'name' => 'Name',
			'price_per_unit' => 'Price Per Unit',
			'quantity' => 'Quantity',
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

		$criteria->compare('equip_id',$this->equip_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price_per_unit',$this->price_per_unit);
		$criteria->compare('quantity',$this->quantity,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}