<?php
class ControllerShippingInpostShipping extends Controller
{
	private $error = array(); 
	
	public function index()
	{
		$this->load->language('shipping/inpost_shipping');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('inpost_shipping', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$text_strings = array(
				'heading_title',
				'text_enabled',
				'text_disabled',
				'text_all_zones',
				'text_none',
				'text_yes',
				'text_no',
				'text_select_all',
				'text_unselect_all',
				'entry_rate',
				'entry_display_weight',
				'entry_weight_class',
				'entry_tax_class',
				'entry_geo_zone',
				'entry_status',
				'entry_sort_order',
				'button_save',
				'button_cancel',
				'tab_general',
			);

		foreach ($text_strings as $text)
		{
			$this->data[$text] = $this->language->get($text);
		}
		//END LANGUAGE

		if (isset($this->error['warning']))  {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      			'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/inpost_shipping', 'token=' . $this->session->data['token'], 'SSL'),
      			'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/inpost_shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		// Standard Parcels
		if (isset($this->request->post['inpost_shipping_standard_parcels_rate'])) {
			$this->data['inpost_shipping_standard_parcels_rate'] = $this->request->post['inpost_shipping_standard_parcels_rate'];
		}
		elseif ($this->config->has('inpost_shipping_standard_parcels_rate')) {
			$this->data['inpost_shipping_standard_parcels_rate'] = $this->config->get('inpost_shipping_standard_parcels_rate');
		}
		else {
			$this->data['inpost_shipping_standard_parcels_rate'] = '2.00';
		}
		
		if (isset($this->request->post['inpost_shipping_standard_parcels_insurance'])) {
			$this->data['inpost_shipping_standard_parcels_insurance'] = $this->request->post['inpost_shipping_standard_parcels_insurance'];
		} elseif ($this->config->has('inpost_shipping_standard_parcels_insurance')) {
			$this->data['inpost_shipping_standard_parcels_insurance'] = $this->config->get('inpost_shipping_standard_parcels_insurance');		
		} else {
			$this->data['inpost_shipping_standard_parcels_insurance'] = '39:0,100:1,250:2.25,500:3.5';
		}
		
		if (isset($this->request->post['inpost_shipping_standard_parcels_status'])) {
			$this->data['inpost_shipping_standard_parcels_status'] = $this->request->post['inpost_shipping_standard_parcels_status'];
		} else {
			$this->data['inpost_shipping_standard_parcels_status'] = $this->config->get('inpost_shipping_standard_parcels_status');
		}


		if (isset($this->request->post['inpost_shipping_display_weight'])) {
			$this->data['inpost_shipping_display_weight'] = $this->request->post['inpost_shipping_display_weight'];
		} else {
			$this->data['inpost_shipping_display_weight'] = $this->config->get('inpost_shipping_display_weight');
		}
		
		if (isset($this->request->post['inpost_shipping_display_insurance'])) {
			$this->data['inpost_shipping_display_insurance'] = $this->request->post['inpost_shipping_display_insurance'];
		} else {
			$this->data['inpost_shipping_display_insurance'] = $this->config->get('inpost_shipping_display_insurance');
		}

		if (isset($this->request->post['inpost_shipping_weight_class_id'])) {
			$this->data['inpost_shipping_weight_class_id'] = $this->request->post['inpost_shipping_weight_class_id'];
		} else {
			$this->data['inpost_shipping_weight_class_id'] = $this->config->get('inpost_shipping_weight_class_id');
		}
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		if (isset($this->request->post['inpost_shipping_tax_class_id'])) {
			$this->data['inpost_shipping_tax_class_id'] = $this->request->post['inpost_shipping_tax_class_id'];
		} else {
			$this->data['inpost_shipping_tax_class_id'] = $this->config->get('inpost_shipping_tax_class_id');
		}

		if (isset($this->request->post['inpost_shipping_geo_zone_id'])) {
			$this->data['inpost_shipping_geo_zone_id'] = $this->request->post['inpost_shipping_geo_zone_id'];
		} else {
			$this->data['inpost_shipping_geo_zone_id'] = $this->config->get('inpost_shipping_geo_zone_id');
		}
		
		if (isset($this->request->post['inpost_shipping_status'])) {
			$this->data['inpost_shipping_status'] = $this->request->post['inpost_shipping_status'];
		} else {
			$this->data['inpost_shipping_status'] = $this->config->get('inpost_shipping_status');
		}
		
		if (isset($this->request->post['inpost_shipping_sort_order'])) {
			$this->data['inpost_shipping_sort_order'] = $this->request->post['inpost_shipping_sort_order'];
		} else {
			$this->data['inpost_shipping_sort_order'] = $this->config->get('inpost_shipping_sort_order');
		}				

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->template = 'shipping/inpost_shipping.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	///
	// validate function
	//
	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'shipping/inpost_shipping'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
