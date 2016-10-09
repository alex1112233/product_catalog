<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
require_once 'Base_Controller.php';

class Items extends Base_Controller {
	
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'item_model' );
		$this->load->helper ( 'url_helper' );
		$this->load->helper ( 'form' );
		
	
	}
	public function index($page=1) {
		
		$this->load->library('table');
		
		$query = $this->item_model->get_items();  //checkin how many items are there
		
		$numPerPage = 5;
		$numOfRows =  $query->num_rows();
		
		//echo $this->table->generate($query);
		
		//pagination
		$this->load->library('pagination');
		
		$config['base_url'] = '/index.php/items/index/';
		$config['total_rows'] = $numOfRows;
		$config['per_page'] = $numPerPage;
		$config['full_tag_open'] = '<p class = "pagination">';
		$config['full_tag_close'] = '</p>';
		
		$this->pagination->initialize($config);
				
		
		$data ['pagination'] = $this->pagination->create_links();
		
		$items = $this->item_model->get_items ($page, $numPerPage)->result();
		
		
		$data ['items'] = array();
		
		foreach($items as $item ){
			
		$initItem = $this->item_model->get_item ( $item->itemId );	
		$convertedItem = $this->item_model->item_convert($initItem);
		array_push($data['items'], $convertedItem);
		
		}
				
	//	$data ['items'] = $this->item_model->get_items ($page, $numPerPage)->result();
		
//		var_dump($data ['items']);
		$data ['title'] = 'Items list 23';
		
		
		$template = $this->twig->loadTemplate('item_list.twig');
		echo $template->render(array('data' => $data, 'bootstrap_path' => $this->bootstrap_path));
	}
	public function view($itemId = FALSE) {
		
		$item = $this->item_model->get_item ( $itemId );
		
		$data = $this->item_model->item_convert($item);
		
		if (empty ( $item )) {
			show_404 ();
		}
		
		
		$template = $this->twig->loadTemplate('itemTry.twig');
		echo $template->render(array('item' => $data, 'bootstrap_path' => $this->bootstrap_path));
		/*
		$this->load->view ( 'templates/items/header', $item );
		$this->load->view ( 'items/v_item', $item );
		$this->load->view ( 'templates/items/footer' );
		*/
	}	
	
	
	public function save($itemData=null){
		
		$itemData=$this->input->post('text');
		
		if($itemData==null)
			$itemData = '{"itemId":"6912a0f4-d6de-40dd-8f6f-f6d79da4931d","name":"Bosch Tassimo Vivy Hot Drinks and Coffee Machine, 1300 W - Black","categoryId":"11052591","stringProps":{"country":"uk","vendorId":"B00REE4HUY","image_url_30":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L.jpg","image_url_31":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L._SL160_.jpg","image_url_32":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L._SL75_.jpg","model":"TAS1252GB","brand":"Bosch","image_type_19":"set_5_mediumImage","image_type_18":"set_5_largeImage","image_type_13":"set_3_mediumImage","image_type_12":"set_3_largeImage","image_type_11":"set_2_smallImage","image_type_10":"set_2_mediumImage","vendorName":"AMAZON","image_type_17":"set_4_smallImage","image_type_16":"set_4_mediumImage","image_type_15":"set_4_largeImage","image_type_14":"set_3_smallImage","image_url_5":"http://ecx.images-amazon.com/images/I/41XfklHXeSL._SL75_.jpg","itemId":"6912a0f4-d6de-40dd-8f6f-f6d79da4931d","image_url_4":"http://ecx.images-amazon.com/images/I/41XfklHXeSL._SL160_.jpg","image_url_7":"http://ecx.images-amazon.com/images/I/41p2W9veylL._SL160_.jpg","image_url_6":"http://ecx.images-amazon.com/images/I/41p2W9veylL.jpg","image_url_9":"http://ecx.images-amazon.com/images/I/51bNpAYnQGL.jpg","image_type_20":"set_5_smallImage","image_url_8":"http://ecx.images-amazon.com/images/I/41p2W9veylL._SL75_.jpg","name":"Bosch Tassimo Vivy Hot Drinks and Coffee Machine, 1300 W - Black","image_type_29":"set_8_smallImage","image_type_24":"set_7_largeImage","description":"Easy to use - fully automatic drink preparation with a press of a button","image_type_23":"set_6_smallImage","image_type_22":"set_6_mediumImage","image_type_21":"set_6_largeImage","image_url_1":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L._SL160_.jpg","image_type_28":"set_8_mediumImage","image_url_0":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L.jpg","image_type_27":"set_8_largeImage","image_url_3":"http://ecx.images-amazon.com/images/I/41XfklHXeSL.jpg","image_type_26":"set_7_smallImage","manufacturer":"Bosch","image_url_2":"http://ecx.images-amazon.com/images/I/41WTXjkMT9L._SL75_.jpg","image_type_25":"set_7_mediumImage","offer_condition_1":"New","offer_condition_2":"Used","offer_condition_0":"New","image_url_10":"http://ecx.images-amazon.com/images/I/51bNpAYnQGL._SL160_.jpg","image_url_11":"http://ecx.images-amazon.com/images/I/51bNpAYnQGL._SL75_.jpg","image_type_31":"set_9_mediumImage","image_url_12":"http://ecx.images-amazon.com/images/I/41sf99pumiL.jpg","image_type_30":"set_9_largeImage","offer_condition_3":"Refurbished","image_url_13":"http://ecx.images-amazon.com/images/I/41sf99pumiL._SL160_.jpg","image_url_14":"http://ecx.images-amazon.com/images/I/41sf99pumiL._SL75_.jpg","image_url_15":"http://ecx.images-amazon.com/images/I/41HWvWYsYGL.jpg","image_url_16":"http://ecx.images-amazon.com/images/I/41HWvWYsYGL._SL160_.jpg","image_url_17":"http://ecx.images-amazon.com/images/I/41HWvWYsYGL._SL75_.jpg","image_url_18":"http://ecx.images-amazon.com/images/I/41oBOgCQSCL.jpg","image_url_19":"http://ecx.images-amazon.com/images/I/41oBOgCQSCL._SL160_.jpg","image_type_0":"largeImage","image_type_32":"set_9_smallImage","image_url_20":"http://ecx.images-amazon.com/images/I/41oBOgCQSCL._SL75_.jpg","image_url_21":"http://ecx.images-amazon.com/images/I/41QLCv5QZvL.jpg","image_url_22":"http://ecx.images-amazon.com/images/I/41QLCv5QZvL._SL160_.jpg","image_url_23":"http://ecx.images-amazon.com/images/I/41QLCv5QZvL._SL75_.jpg","image_url_24":"http://ecx.images-amazon.com/images/I/41fnvcSoaIL.jpg","image_type_9":"set_2_largeImage","image_url_25":"http://ecx.images-amazon.com/images/I/41fnvcSoaIL._SL160_.jpg","image_type_8":"set_1_smallImage","image_url_26":"http://ecx.images-amazon.com/images/I/41fnvcSoaIL._SL75_.jpg","image_type_7":"set_1_mediumImage","image_url_27":"http://ecx.images-amazon.com/images/I/31ricsuovgL.jpg","image_type_6":"set_1_largeImage","image_url_28":"http://ecx.images-amazon.com/images/I/31ricsuovgL._SL160_.jpg","image_type_5":"set_0_smallImage","image_url_29":"http://ecx.images-amazon.com/images/I/31ricsuovgL._SL75_.jpg","image_type_4":"set_0_mediumImage","image_type_3":"set_0_largeImage","categoryId":"11052591","image_type_2":"smallImage","image_type_1":"mediumImage"},"decimalProps":{"offer_listPrice_0":"3999.0","offer_offerPrice_0":"0.0","offer_listPrice_2":"0.0","offer_offerPrice_2":"3371.0","offer_listPrice_1":"0.0","offer_offerPrice_1":"3999.0","offer_listPrice_3":"0.0","offer_offerPrice_3":"3961.0","salesRank":"97"},"dateProps":{"offer_createdAt_2":"2016-01-19 00:01:26","offer_createdAt_1":"2016-01-19 00:01:26","offer_createdAt_0":"2016-01-19 00:01:26","offer_createdAt_3":"2016-01-19 00:01:26"},"booleanProps":{},"blobProps":{},"textProps":{"review_srcUrl_0":"http://www.amazon.co.uk/reviews/iframe?akid=07SC1MMQDZFJADR6XP82&alinkCode=sp1&asin=B00REE4HUY&atag=arituk-21&exp=2016-07-19T22%3A01%3A24Z&v=2&sig=adwtdJRGky0%2FYanXIWS9Xo4MTPyeqkKuhloBEvL0WR8%3D","vendorUrl":"http://www.amazon.co.uk/Bosch-Tassimo-Drinks-Coffee-Machine/dp/B00REE4HUY%3Fpsc%3D1%26SubscriptionId%3D07SC1MMQDZFJADR6XP82%26tag%3Darituk-21%26linkCode%3Dsp1%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB00REE4HUY","vendorReview":"The Bosch TAS1252GB hot drinks machine comes with many handy features. First of all an auto off feature will allow after each brewing process, the appliance will automatically switch to standby mode. This will help reduce the power consumption of your Tassimo without any additional effort and make savings in your household budget. When purchasing your Tassimo, you will receive the service T disc. This includes the appropriate barcode that is required to clean the brewing system and perform the automatic descaling program. Another feature is the innovative flow-through heater which does not take long to heat up, and can prepare your first hot drink immediately. Water does not have to be heated up between different drinks too."},"itemCategories":[{"id":"242416011","parentId":"11052591","name":"Featured Categories"},{"id":"11052671","parentId":"11052591","name":"Garden & Outdoors"},{"id":"11052681","parentId":"11052591","name":"Home & Kitchen"},{"id":"11052601","parentId":"11052591","name":"Special Features"},{"id":"11052641","parentId":"11052591","name":"Substores"},{"id":"11052591","parentId":"3146281","name":"Home & Garden"},{"id":"10706951","parentId":"10706881","name":"Espresso & Cappuccino Machines"},{"id":"3538290031","parentId":"10706881","name":"Coffee Capsule Machines"},{"id":"3538291031","parentId":"10706881","name":"Coffee Pod Machines"}]}';
		
		$data = null;
		$this->item_model->save($itemData);
		$this->load->view('templates/items/header', $data);
		$this->load->view ( 'items/v_success' );
		$this->load->view('templates/items/footer');
		
		
	}
	
	public function load(){
	
		
			
			$this->load->view ( 'items/v_save_item' );
			$this->load->view('templates/items/footer');
	
	
	}
}
