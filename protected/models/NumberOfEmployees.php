<?php

/**
 * This is the model class for table "number_of_employees".
 *
 * The followings are the available columns in table 'number_of_employees':
 * @property integer $store_id
 * @property string $city
 * @property string $state
 * @property string $number_of_employees
 */
class NumberOfEmployees extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NumberOfEmployees the static model class
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
		return 'number_of_employees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'required'),
			array('store_id', 'numerical', 'integerOnly'=>true),
			array('city, state', 'length', 'max'=>45),
			array('number_of_employees', 'length', 'max'=>21),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('store_id, city, state, number_of_employees', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'store_id' => 'Store',
			'city' => 'City',
			'state' => 'State',
			'number_of_employees' => 'Number Of Employees',
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
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('number_of_employees',$this->number_of_employees,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}