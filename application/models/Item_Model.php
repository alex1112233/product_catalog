<?php
/**
 * Includes the Item_Model class as well as the required sub-classes
 * @package codeigniter.application.models
 */

/**
 * Item_Model extends codeigniters base CI_Model to inherit all codeigniter magic!
 * Item is saved in attribute-value pairs of different types.
 * Every attribute is checked
 * in ITEMS.Attribute table. If it exists that attribute id is returned otherwise the new attribute is created
 *
 * @package codeigniter.application.models
 */
class Item_Model extends CI_Model {
	/*
	 * A private variable to represent each column in the database
	 */
	private $_itemId;
	private $_name;
	private $_categoryId;
	function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	/*
	 * SET's & GET's
	 * Set's and get's allow you to retrieve or set a private variable on an object
	 */
	
	/**
	 * ID
	 */
	
	/**
	 *
	 * @return int [$this->_id] Return this objects ID
	 */
	public function getId() {
		return $this->_itemId;
	}
	
	/**
	 *
	 * @param
	 *        	int Integer to set this objects ID to
	 */
	public function setId($value) {
		$this->_itemId = $value;
	}
	
	/**
	 * Item NAME
	 */
	
	/**
	 *
	 * @return string [$this->_name] Return this objects username
	 */
	public function getName() {
		return $this->_name;
	}
	
	/**
	 *
	 * @param
	 *        	string String to set this objects username to
	 */
	public function setName($value) {
		$this->_name = $value;
	}
	
	/**
	 * PASSWORD
	 */
	
	/**
	 *
	 * @return string [$this->_categoryId] Return this item category
	 */
	public function getCatergoryId() {
		return $this->_categoryId;
	}
	
	/**
	 *
	 * @param
	 *        	string String to set this objects password to
	 */
	public function setCategoryId($value) {
		$this->_categoryId = $value;
	}
	
	/*
	 * Class Methods
	 */
	
	public function set_item_data($itemId, $name, $categoryId) {
		$data = array (
				'itemId' => $itemId,
				'name' => $name,
				'categoryId' => $categoryId
		);
		
		
		$query = $this->db->query ( "SELECT itemId FROM item WHERE itemId='" . $itemId . "'" );
		if ($query->num_rows () == 0){
		   $this->db->insert ( 'item', $data );	
		   return true;  //updated item
		}else{
			$this->db->set('name', $name);
			$this->db->set('categoryId', $categoryId);
            $this->db->where('itemId', $itemId);
            $this->db->update('item'); 
			return false;
		}
		
		
	}
	private function set_category($categoryId, $parentId, $name) {
		$sql = "SELECT categoryId FROM category WHERE categoryId='" . $categoryId . "'" ;
		echo  $sql."\n";
		$query = $this->db->query ($sql);
	
		//if the category is not present yet	
		if ($query->num_rows () == 0) {
			$sql = "INSERT INTO category (categoryId, parentId, name) VALUES ('" . $categoryId . "' , '" . $parentId . "', " . $this->db->escape ( $name ) . ")";
			$this->db->query ( $sql );
		}
	}
	private function set_attr($itemId, $attr_type, $attr_name, $attr_value) {
	
		// check if an attribute with this type & name exists already
		$sql = "SELECT attributeId FROM attribute WHERE type='" . $attr_type . "' and name='" . $attr_name . "'";
		echo $sql."\n";;
		$query = $this->db->query ($sql);
	
		$attributeId = null;
		if ($query->num_rows () > 0) {
			$attributeId = $query->row()->attributeId;
			echo 'found attribute id '.$attributeId.' for '. $attr_type .' and name='. $attr_name."\n";
			
		}
		if ($attributeId == null) {
			$attributeId = uniqid ();
			$sql = "INSERT INTO attribute (attributeId, name, type) VALUES ('" . $attributeId . "' , " . $this->db->escape ( $attr_name ) . ", " . $this->db->escape ( $attr_type ) . ")";
			echo $sql."\n";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "string") {
			echo '$attr_value = '.$attr_value."\n";
			
			// chek if this is an update 
			$sqlExistingAttr = "Select itemId FROM item_attr_string WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
			
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_string SET stringVal = ". $this->db->escape ( $attr_value ) ." WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			
			$sql = "INSERT INTO item_attr_string (itemId, attributeId, stringVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			echo $sql."\n";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "date") {
			
			// chek if this is an update
			$sqlExistingAttr = "Select itemId FROM item_attr_date WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
				
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_date SET dateVal = ". $this->db->escape ( $attr_value ).", ' WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			$sql = "INSERT INTO item_attr_date (itemId, attributeId, dateVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			echo $sql."\n";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "number") {
			
			// chek if this is an update
			$sqlExistingAttr = "Select itemId FROM item_attr_decimal WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
				
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_decimal SET numberVal = ". $this->db->escape ( $attr_value ) ." WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			$sql = "INSERT INTO item_attr_decimal (itemId, attributeId, numberVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			echo $sql."\n";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "boolean") {
			// chek if this is an update
			$sqlExistingAttr = "Select itemId FROM item_attr_boolean WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
				
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_boolean SET boolVal = ". $this->db->escape ( $attr_value ) ." WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			$sql = "INSERT INTO item_attr_boolean (itemId, attributeId, boolVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "blob") {
			// chek if this is an update
			$sqlExistingAttr = "Select itemId FROM item_attr_blob WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
				
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_blob SET blobVal = ". $this->db->escape ( $attr_value ) ." WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			$sql = "INSERT INTO item_attr_blob (itemId, attributeId, blobVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			$this->db->query ( $sql );
		}
	
		if ($attr_type == "text") {
			// chek if this is an update
			$sqlExistingAttr = "Select itemId FROM item_attr_text WHERE itemId='".$itemId."' and attributeId='".$attributeId."'";
				
			$query = $this->db->query ( $sqlExistingAttr );
			if($query->num_rows () > 0){
				$sql = "UPDATE item_attr_text SET textVal = ". $this->db->escape ( $attr_value ) ." WHERE itemId = '" . $itemId . "' and attributeId = '" . $attributeId . "'";
				$this->db->query ( $sql );
				return;  //if the update of an attribute is done we do not need to insert it again
			}
			$sql = "INSERT INTO item_attr_text (itemId, attributeId, textVal) VALUES ('" . $itemId . "', '" . $attributeId . "' , " . $this->db->escape ( $attr_value ) . ")";
			$this->db->query ( $sql );
		}
	}
	
	/**
	 * Commit method, this will comment the entire object to the database
	 */
	public function save($itemData) {
		
		$data = json_decode ( $itemData, true );
		
		$itemId = $data ['itemId'];
		$categoryId = $data ['categoryId'];
		$name = $data ['name'];
		
		// save categories first
		foreach ( $data ['itemCategories'] as $cat ){
			$parentId = null;
		    if(array_key_exists('parentId', $cat)) $parentId = $cat ['parentId'];
		    $this->set_category ( $cat ['id'],$parentId, $cat ['name'] );
		}
			
			// save item main data
		 $this->set_item_data ( $itemId, $name, $categoryId );
		
		// save string props
		foreach ( array_keys ( $data ['stringProps'] ) as $strAttrKey ){
			echo '$strAttrKey='.$strAttrKey.' $data[$strAttrKey]='.$data['stringProps'][$strAttrKey]."\n";
			$this->set_attr ( $itemId, 'string', $strAttrKey, $data['stringProps'][$strAttrKey] );
		}
			
			//TODO:see how to deal with MSQL time stamps save date props
		foreach ( array_keys ( $data ['dateProps'] ) as $dateAttrKey ){
			$this->set_attr ( $itemId, 'date', $dateAttrKey,  strtotime($data['dateProps'][$dateAttrKey]) );
		}	
			// save decimal props
		foreach ( array_keys ( $data ['decimalProps'] ) as $numAttrKey ){
			$this->set_attr ( $itemId, 'number', $numAttrKey, $data['decimalProps'][$numAttrKey] );
		}	
			// save boolean props
		foreach ( array_keys ( $data ['booleanProps'] ) as $boolAttrKey ){
			$this->set_attr ( $itemId, 'boolean', $boolAttrKey, $data [$boolAttrKey] );
		}	
			// save test props
		foreach ( array_keys ( $data ['textProps'] ) as $textAttrKey ){
			$this->set_attr ( $itemId, 'text', $textAttrKey, $data['textProps'] [$textAttrKey] );
		}	
			// save blob props
		foreach ( array_keys ( $data ['blobProps'] ) as $blobAttrKey ){
			$this->set_attr ( $itemId, 'blob', $blobAttrKey, $data ['blobProps'] [$blobAttrKey] );
	
		}
	}
	
	
	public function get_items( $page = FALSE, $numPerPage = FALSE) {
		
			
			$sql = "SELECT * FROM item order by itemId";
			
			if(is_numeric($page)  && is_numeric($numPerPage) ){
// 				echo "\n row num: ".($page-1)*$numPerPage."\n";
// 				echo "\n $page is numeric and $numPerPage also see calcs (($page-1)*$numPerPage), ($page*$numPerPage)";
				$sql = "SELECT * FROM item order by itemId LIMIT ?,?;";				
				$query = $this->db->query ($sql,  array(($page-1)*1, $numPerPage) );
				
			} else {
// 				echo "\n $page is not numiric ???";
				$query = $this->db->query ( $sql );
			}
			
			
			return $query;
		
	}
	
	
	public function get_item( $itemId) {
	
		$item = null;	
		$sql = "SELECT * FROM item WHERE itemId='".$itemId."'";
			
		$query = $this->db->query ($sql);
		$item['main'] = $query->row();
	
		$sqlStrVal = "select att.name, iav.stringVal from item_attr_string iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryStrVal = $this->db->query ($sqlStrVal);
		$item['stringArr']= $queryStrVal->result_array();
		
		$sqlDateVal = "select att.name, iav.dateVal from item_attr_date iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryDateVal = $this->db->query ($sqlDateVal);
		$item['dateArr']= $queryDateVal->result_array();
		
		$sqlNumVal = "select att.name, iav.numberVal from item_attr_decimal iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryDecimalVal = $this->db->query ($sqlNumVal);
		$item['numberArr']= $queryDecimalVal->result_array();
		
		$sqlTextVal = "select att.name, iav.textVal from item_attr_text iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryTextVal = $this->db->query ($sqlTextVal);
		$item['textArr']= $queryTextVal->result_array();
		
		$sqlBoolVal = "select att.name, iav.boolVal from item_attr_boolean iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryBoolVal = $this->db->query ($sqlBoolVal);
		$item['boolArr']= $queryBoolVal->result_array();
		
		$sqlBlobVal = "select att.name, iav.blobVal from item_attr_blob iav inner join attribute att on att.attributeId=iav.attributeId where itemId='".$itemId."'";
		$queryBlobVal = $this->db->query ($sqlBoolVal);
		$item['blobArr']= $queryBlobVal->result_array();
		
			
		return $item;
	
	}
	
	public function item_convert($item){
		
		$convertedItem = null;
		
		$stringArr = array_merge($item['stringArr'], $item['textArr']);
		
		foreach ($stringArr as $nameValue){				
			
			if (strpos ( $nameValue['name'], 'country' )!== false){				
				$convertedItem ['country'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos ( $nameValue['name'], 'description' )!== false){		
				
				$convertedItem ['description'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];					
			}
			
			if (strpos ( $nameValue['name'], 'name' )!== false){
				$convertedItem ['name'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos ( $nameValue['name'], 'itemId' )!== false){
				$convertedItem ['itemId'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos ( $nameValue['name'], 'manufacturer' )!== false){
				$convertedItem ['manufacturer'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos ( $nameValue['name'], 'model' )!== false){
				$convertedItem ['model'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos ( $nameValue['name'], 'brand' )!== false){
				$convertedItem ['brand'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			}
			
			if (strpos($nameValue['name'], 'vendorReview')!== false){
				
				$convertedItem ['vendorReview'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
					
			}
			
			if (strpos($nameValue['name'], 'vendorUrl')!== false ){
				$convertedItem ['vendorUrl'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
			
			}
			
			   //features
			if (preg_match ( '/feature_(\d+)/', $nameValue['name'], $matches )) {
				$suffixNum = $matches [1];				
						
				$convertedItem['feature'][$suffixNum] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				
			}	
			
				// images
			if (preg_match ( '/image_set_(\d+)/', $nameValue['name'], $matches )) {
				$suffixNum = $matches [1];
								
				if (strripos ( $nameValue['name'], 'smallImage' )!== false) {
					
					 $convertedItem['imageSet'][$suffixNum]['smallImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
				
				if (strripos ( $nameValue['name'], 'mediumImage' )!== false) {
						
					$convertedItem['imageSet'][$suffixNum]['mediumImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
				
				if (strripos ( $nameValue['name'], 'largeImage' )!== false) {
				
					$convertedItem['imageSet'][$suffixNum]['largeImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
				
				if (strripos ( $nameValue['name'], 'swatchImage' )!== false) {
				
					$convertedItem['imageSet'][$suffixNum]['swatchImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
				
				if (strripos ( $nameValue['name'], 'tinyImage' )!== false) {
				
					$convertedItem['imageSet'][$suffixNum]['tinyImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
				
				if (strripos ( $nameValue['name'], 'thumbnailImage' )!== false) {
				
					$convertedItem['imageSet'][$suffixNum]['thumbnailImage'] = isset($nameValue['stringVal'])?$nameValue['stringVal']:$nameValue['textVal'];				
				}
			}
			
		}
			
			foreach ($item['numberArr'] as $nameValue){
				//prices
				if (preg_match ( '/offerPrice_(\d+)/', $nameValue['name'], $matches )) {
				    $suffixNum = $matches [1];
			
				    $itemCondition = $this->findValue( 'offer_condition_' . $suffixNum, $item ['stringArr']);
						
					if (strpos((string)$itemCondition, 'New')!== false && intval( $nameValue['numberVal'])>0){
					$convertedItem ['priceNew'] = $nameValue['numberVal'];
					
					}
				
					if (strpos((string)$itemCondition, 'Used')!== false && intval( $nameValue['numberVal'])>0){
					$convertedItem ['priceUsed'] = $nameValue['numberVal'];
						
					}
				
					if (strpos((string)$itemCondition, 'Refurbished')!== false && intval( $nameValue['numberVal'])>0){
					$convertedItem ['Refurbished'] = $nameValue['numberVal'];
				
					}			
			
				}
			}			
		
		return $convertedItem;
	}
	
	
	/**
	 * finds values in the nested array ([1]=>{'name'=>name, 'value'=value}, [2]=...)
	 * the value is retrieved by index as its name can be stringVal, dateVal ets
	 * @param unknown $name
	 * @param unknown $nameValueArr
	 */
	private function findValue($name, $nameValueArr){
		
		foreach ($nameValueArr as $nameValue) {
			if($nameValue['name'] == $name){
				$result = array_values($nameValue);
				return $result[1];
			}
				
		}
		
	}
	
}