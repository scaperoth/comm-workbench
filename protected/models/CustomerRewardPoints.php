<?php

/**
 * This is the model class for table "customer_reward_points".
 *
 * The followings are the available columns in table 'customer_reward_points':
 * @property integer $user_id
 * @property string $f_name
 * @property string $l_name
 * @property string $reward_points
 */
class CustomerRewardPoints extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerRewardPoints the static model class
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
		return 'customer_reward_points';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('f_name, l_name', 'length', 'max'=>45),
			array('reward_points', 'length', 'max'=>47),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, f_name, l_name, reward_points', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'f_name' => 'F Name',
			'l_name' => 'L Name',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('f_name',$this->f_name,true);
		$criteria->compare('l_name',$this->l_name,true);
		$criteria->compare('reward_points',$this->reward_points,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}