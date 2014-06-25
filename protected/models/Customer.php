<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $cust_id
 * @property string $reward_points
 *
 * The followings are the available model relations:
 * @property User $cust
 * @property Item[] $items
 * @property Sale[] $sales
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reward_points', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cust_id, reward_points', 'safe', 'on'=>'search'),
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
			'cust' => array(self::BELONGS_TO, 'User', 'cust_id'),
			'items' => array(self::MANY_MANY, 'Item', 'pre_order(preorder_cust_id, preorder_item_id)'),
			'sales' => array(self::HAS_MANY, 'Sale', 'sale_cust_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cust_id' => 'Cust',
			'reward_points' => 'Reward Points',
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

		$criteria->compare('cust_id',$this->cust_id);
		$criteria->compare('reward_points',$this->reward_points,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}