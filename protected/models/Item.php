<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property integer $item_id
 * @property string $name
 * @property string $price
 * @property string $release_date
 * @property string $sale_price
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property Produces[] $produces
 * @property Sale[] $sales
 * @property Store[] $stores
 */
class Item extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Item the static model class
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
		return 'item';
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
			array('price, sale_price', 'length', 'max'=>25),
			array('release_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_id, name, price, release_date, sale_price', 'safe', 'on'=>'search'),
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
			'customers' => array(self::MANY_MANY, 'Customer', 'pre_order(preorder_item_id, preorder_cust_id)'),
			'produces' => array(self::HAS_MANY, 'Produces', 'production_item_id'),
			'sales' => array(self::HAS_MANY, 'Sale', 'sale_item_id'),
			'stores' => array(self::MANY_MANY, 'Store', 'store_item(store_item_id, item_store_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'item_id' => 'Item',
			'name' => 'Name',
			'price' => 'Price',
			'release_date' => 'Release Date',
			'sale_price' => 'Sale Price',
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

		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('release_date',$this->release_date,true);
		$criteria->compare('sale_price',$this->sale_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}