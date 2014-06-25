<?php

/**
 * This is the model class for table "sale".
 *
 * The followings are the available columns in table 'sale':
 * @property integer $sale_cust_id
 * @property integer $sale_item_id
 * @property integer $sale_store_id
 * @property integer $sale_emp_id
 *
 * The followings are the available model relations:
 * @property Customer $saleCust
 * @property Item $saleItem
 * @property Employee $saleEmp
 * @property Store $saleStore
 */
class Sale extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sale the static model class
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
		return 'sale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sale_cust_id, sale_item_id, sale_store_id', 'required'),
			array('sale_cust_id, sale_item_id, sale_store_id, sale_emp_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('sale_cust_id, sale_item_id, sale_store_id, sale_emp_id', 'safe', 'on'=>'search'),
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
			'saleCust' => array(self::BELONGS_TO, 'Customer', 'sale_cust_id'),
			'saleItem' => array(self::BELONGS_TO, 'Item', 'sale_item_id'),
			'saleEmp' => array(self::BELONGS_TO, 'Employee', 'sale_emp_id'),
			'saleStore' => array(self::BELONGS_TO, 'Store', 'sale_store_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sale_cust_id' => 'Sale Cust',
			'sale_item_id' => 'Sale Item',
			'sale_store_id' => 'Sale Store',
			'sale_emp_id' => 'Sale Emp',
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

		$criteria->compare('sale_cust_id',$this->sale_cust_id);
		$criteria->compare('sale_item_id',$this->sale_item_id);
		$criteria->compare('sale_store_id',$this->sale_store_id);
		$criteria->compare('sale_emp_id',$this->sale_emp_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}