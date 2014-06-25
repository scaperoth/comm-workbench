<?php

/**
 * This is the model class for table "manufacturer".
 *
 * The followings are the available columns in table 'manufacturer':
 * @property integer $manu_id
 * @property string $name
 * @property string $street_address
 * @property string $zip_code
 * @property string $city
 * @property string $state
 *
 * The followings are the available model relations:
 * @property Produces[] $produces
 */
class Manufacturer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Manufacturer the static model class
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
		return 'manufacturer';
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
			array('zip_code, city, state', 'length', 'max'=>45),
			array('street_address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('manu_id, name, street_address, zip_code, city, state', 'safe', 'on'=>'search'),
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
			'produces' => array(self::HAS_MANY, 'Produces', 'item_manufacturer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'manu_id' => 'Manu',
			'name' => 'Name',
			'street_address' => 'Street Address',
			'zip_code' => 'Zip Code',
			'city' => 'City',
			'state' => 'State',
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

		$criteria->compare('manu_id',$this->manu_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('street_address',$this->street_address,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}