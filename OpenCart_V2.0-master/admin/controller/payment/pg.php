<?php
class ControllerPaymentPG extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('payment/PG');
		 $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
		$data['heading_title']=  $this->language->get('PG');
		$data['text_extension']=  $this->language->get('Extensions');
		$data['text_success']=  $this->language->get('Success: You have modified PG account details!');
		$data['text_edit']=  $this->language->get('Edit PG');
		$data['text_PG']=  $this->language->get('<img src="view/image/payment/PG.png" alt="PG" title="PG" style="border: 1px solid #EEEEEE;" />');
		$data['entry_pay_id']=  $this->language->get('PG Pay ID');
		$data['entry_salt']=  $this->language->get('Salt');
		$data['entry_test']=  $this->language->get('Test Mode');
		$data['entry_total']=  $this->language->get('Total');
		$data['entry_order_status']=  $this->language->get('Order Status');
		$data['entry_geo_zone']=  $this->language->get('Geo Zone');
		$data['entry_status']=  $this->language->get('Status');
		$data['entry_sort_order']=  $this->language->get('Sort Order');
		$data['help_salt']=  $this->language->get('PG Salt');
		$data['text_yes']=  $this->language->get('text_yes');
		$data['text_no']=  $this->language->get('text_no');
		$data['help_total']=  $this->language->get('The checkout total the order must reach before this payment method becomes active.');
		$data['error_permission']=  $this->language->get('Warning: You do not have permission to modify payment PG!');
		$data['error_pay_id']=  $this->language->get('PG Pay ID required!');
		$data['error_salt']=  $this->language->get('Salt required!');
		$data['text_all_zones']=  $this->language->get('text_all_zones');
		$data['text_enabled']=  $this->language->get('text_enabled');
		$data['text_disabled']=  $this->language->get('text_disabled');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('PG', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment',  'token=' . $this->session->data['token'], 'SSL'));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['pay_id'])) {
            $data['error_pay_id'] = $this->error['pay_id'];
        } else {
            $data['error_pay_id'] = '';
        }

        if (isset($this->error['salt'])) {
            $data['error_salt'] = $this->error['salt'];
        } else {
            $data['error_salt'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard',  'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/payment',  'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/PG', 'token=' . $this->session->data['token'], 'SSL') 
        );

        $data['action'] = $this->url->link('payment/PG', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment',  'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['PG_pay_id'])) {
            $data['PG_pay_id'] = $this->request->post['PG_pay_id'];
        } else {
            $data['PG_pay_id'] = $this->config->get('PG_pay_id');
        }

        if (isset($this->request->post['PG_salt'])) {
            $data['PG_pay_id'] = $this->request->post['PG_salt'];
        } else {
            $data['PG_salt'] = $this->config->get('PG_salt');
        }

        if (isset($this->request->post['PG_test'])) {
            $data['PG_test'] = $this->request->post['PG_test'];
        } else {
            $data['PG_test'] = $this->config->get('PG_test');
        }

        if (isset($this->request->post['PG_total'])) {
            $data['PG_total'] = $this->request->post['PG_total'];
        } else {
            $data['PG_total'] = $this->config->get('PG_total');
        }

        if (isset($this->request->post['PG_order_status_id'])) {
            $data['PG_order_status_id'] = $this->request->post['PG_order_status_id'];
        } else {
            $data['PG_order_status_id'] = $this->config->get('PG_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['PG_geo_zone_id'])) {
            $data['PG_geo_zone_id'] = $this->request->post['PG_geo_zone_id'];
        } else {
            $data['PG_geo_zone_id'] = $this->config->get('PG_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['PG_status'])) {
            $data['PG_status'] = $this->request->post['PG_status'];
        } else {
            $data['PG_status'] = $this->config->get('PG_status');
        }

        if (isset($this->request->post['PG_sort_order'])) {
            $data['PG_sort_order'] = $this->request->post['PG_sort_order'];
        } else {
            $data['PG_sort_order'] = $this->config->get('PG_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer'); 
        $this->response->setOutput($this->load->view('payment/PG.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/PG')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['PG_pay_id']) {
            $this->error['pay_id'] = $this->language->get('error_pay_id');
        }

        if (!$this->request->post['PG_salt']) {
            $this->error['salt'] = $this->language->get('error_salt');
        }

        return !$this->error;
    }
}
