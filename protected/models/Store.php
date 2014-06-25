<?php

/**
 * This is the model class for table "store".
 *
 * The followings are the available columns in table 'store':
 * @property integer $store_id
 * @property string $street_address
 * @property string $zip_code
 * @property string $city
 * @property string $state
 * @property string $size
 * @property integer $num_emp
 *
 * The followings are the available model relations:
 * @property Employee[] $employees
 * @property Sale[] $sales
 * @property Equiment[] $equiments
 * @property Item[] $items
 * @property Warehouse[] $warehouses
 * @property Employee[] $employees1
 */
class Store extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Store the static model class
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
		return 'store';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('street_address, zip_code, state', 'required'),
			array('num_emp', 'numerical', 'integerOnly'=>true),
			array('zip_code, city, state, size', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('store_id, street_address, zip_code, city, state, size, num_emp', 'safe', 'on'=>'search'),
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
			'employees' => array(self::MANY_MANY, 'Employee', 'manages(manager_store_id, store_mgr_id)'),
			'sales' => array(self::HAS_MANY, 'Sale', 'sale_store_id'),
			'equiments' => array(self::MANY_MANY, 'Equiment', 'store_equipment(equipment_store_id, store_equip_id)'),
			'items' => array(self::MANY_MANY, 'Item', 'store_item(item_store_id, store_item_id)'),
			'warehouses' => array(self::MANY_MANY, 'Warehouse', 'store_warehouse(warehouse_store_id, store_warehouse_id)'),
			'employees1' => array(self::MANY_MANY, 'Employee', 'works(employee_store_id, store_emp_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'store_id' => 'Store',
			'street_address' => 'Street Address',
			'zip_code' => 'Zip Code',
			'city' => 'City',
			'state' => 'State',
			'size' => 'Size',
			'num_emp' => 'Num Emp',
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

		$criteria->compare('store_id',$this->store_id);
		$criteria->compare('street_address',$this->street_address,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('num_emp',$this->num_emp);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}