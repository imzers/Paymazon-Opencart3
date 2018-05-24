<?php
/*
status code
-- PROCESSING
-- WAITING
-- FAILED
-- CANCELED
-- COMPLETED
-- SUCCESS
*/
class ControllerExtensionPaymentpaymazonkbankinst41 extends Controller {
	public $error = FALSE, $error_msg = array();
	
	// Load Paymazon Library
	private function loadPaymazonLib() {
        require_once(DIR_SYSTEM . 'library/paymazon-php/Paymazon.php');
		Paymazon_Config::setMerchantID($this->config->get('payment_paymazonkbankinst41_merchant_id'));
		Paymazon_Config::setSharedKey($this->config->get('payment_paymazonkbankinst41_shared_key'));
		Paymazon_Config::setEnvironment($this->config->get('payment_paymazonkbankinst41_environment'));
		Paymazon_Config::setPaymentGateway($this->config->get('payment_paymazonkbankinst41_pgcode'));
    }
	private function getOrderData() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$this->language->load('extension/payment/paymazonkbankinst41');
        $this->loadPaymazonLib();
        //get order info
        $this->load->model('checkout/order');
        $order_data = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		return $order_data;
	}
	private function getPaymentStatus($payment_status) {
		$status = "";
		switch (strtoupper($payment_status)) {
			case 'FAILED':
				$status = $this->config->get('payment_paymazonkbankinst41_failed_status');
			break;
			case 'CANCELED':
				$status = $this->config->get('payment_paymazonkbankinst41_canceled_status');
			break;
			case 'COMPLETED':
			case 'SUCCESS':
				$status = $this->config->get('payment_paymazonkbankinst41_success_status');
			break;
			case 'WAITING':
			default:
				$status = $this->config->get('payment_paymazonkbankinst41_pending_status');
			break;
		}
		return $status;
	}
	private function getRequestParams() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$this->language->load('extension/payment/paymazon');
		$this->loadPaymazonLib();
		//--------------------------------------------------
		$Paymazon = new Paymazon(Paymazon_Config::$CONFIG);
		if (!$this->error) {
			if (!isset(Paymazon_Config::$CONFIG['pg_code'])) {
				$this->error = true;
				$this->error_msg[] = "Module not have pg-code";
			}
		}
	}
	//===============================================================================================================
	public function paymentnotify() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$this->load->model('checkout/order');
		$this->language->load('extension/payment/paymazon');
		$this->loadPaymazonLib();
		$query_params = array(
			'input'						=> array(),
			'new_payment_status'		=> FALSE,
		);
		//--------------------------------------------------
		$Paymazon = new Paymazon(Paymazon_Config::$CONFIG);
		if (!$this->error) {
			if (!isset(Paymazon_Config::$CONFIG['pg_code'])) {
				$this->error = true;
				$this->error_msg[] = "Module not have pg-code";
			}
		}
		if (!$this->error) {
			try {
				$input_params = $Paymazon->get_php_input_request()['body'];
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Error exception while collectiong php input-params.";
			}
		}
		if (!$this->error) {
			$query_params['input'] = array(
				'request_id'				=> (isset($input_params['request_id']) ? $input_params['request_id'] : ''),
				'payment_id'				=> (isset($input_params['payment_id']) ? $input_params['payment_id'] : ''),
				'payment_type'				=> (isset($input_params['payment_type']) ? $input_params['payment_type'] : ''),
				'payment_method'			=> (isset($input_params['payment_method']) ? $input_params['payment_method'] : ''),
				'bank_code'					=> (isset($input_params['bank_code']) ? $input_params['bank_code'] : ''),
				'payment_account'			=> (isset($input_params['payment_account']) ? $input_params['payment_account'] : ''),
				'paid_amount'				=> (isset($input_params['paid_amount']) ? $input_params['paid_amount'] : ''),
				'currency'					=> (isset($input_params['currency']) ? $input_params['currency'] : ''),
				'current_state'				=> (isset($input_params['current_state']) ? $input_params['current_state'] : ''),
				'created_date'				=> (isset($input_params['created_date']) ? $input_params['created_date'] : ''),
				'authorized_date'			=> (isset($input_params['authorized_date']) ? $input_params['authorized_date'] : ''),
				'captured_date'				=> (isset($input_params['captured_date']) ? $input_params['captured_date'] : ''),
				'payment_result_status'		=> (isset($input_params['payment_result_status']) ? $input_params['payment_result_status'] : ''),
				'payment_result_code'		=> (isset($input_params['payment_result_code']) ? $input_params['payment_result_code'] : ''),
				'payment_result_desc'		=> (isset($input_params['payment_result_desc']) ? $input_params['payment_result_desc'] : ''),
				'payment_result_message'	=> (isset($input_params['payment_result_message']) ? $input_params['payment_result_message'] : ''),
				'payment_encrypt'			=> (isset($input_params['payment_encrypt']) ? $input_params['payment_encrypt'] : ''),
			);
			$query_params['input']['request_id'] = ((is_numeric($query_params['input']['request_id'])  || is_string($query_params['input']['request_id'])) ? sprintf("%s", $query_params['input']['request_id']) : '');
			$query_params['input']['payment_id'] = ((is_numeric($query_params['input']['payment_id'])  || is_string($query_params['input']['payment_id'])) ? sprintf("%s", $query_params['input']['payment_id']) : '');
			$query_params['input']['payment_type'] = ((is_numeric($query_params['input']['payment_type'])  || is_string($query_params['input']['payment_type'])) ? sprintf("%s", $query_params['input']['payment_type']) : '');
			$query_params['input']['payment_method'] = ((is_numeric($query_params['input']['payment_method'])  || is_string($query_params['input']['payment_method'])) ? sprintf("%s", $query_params['input']['payment_method']) : '');
			$query_params['input']['payment_result_status'] = ((is_numeric($query_params['input']['payment_result_status'])  || is_string($query_params['input']['payment_result_status'])) ? sprintf("%s", $query_params['input']['payment_result_status']) : '');
			$query_params['input']['payment_result_code'] = ((is_numeric($query_params['input']['payment_result_code'])  || is_string($query_params['input']['payment_result_code'])) ? sprintf("%s", $query_params['input']['payment_result_code']) : '');
			$query_params['input']['payment_encrypt'] = ((is_numeric($query_params['input']['payment_encrypt'])  || is_string($query_params['input']['payment_encrypt'])) ? sprintf("%s", $query_params['input']['payment_encrypt']) : '');
			//----
			try {
				$query_params['wheres'] = array(
					'paymazon_payment_code'		=> $this->config->get('payment_paymazonkbankinst41_pgcode'),
					'paymazon_payment_shopid'	=> $this->config->get('payment_paymazonkbankinst41_shopid'),
					'paymazon_payment_id'		=> $query_params['input']['payment_id'],
				);
				$query_params['transaction_data'] = $this->model_extension_payment_paymazonkbankinst41->get_order_data_by('payment', '', $query_params['wheres']);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Cannot get data of paymazon transaction by payment-id (Query data)";
			}
		}
		if (!$this->error) {
			if (!isset($query_params['transaction_data']['paymazon_payment_status']) || !isset($query_params['transaction_data']['order_id'])) {
				$this->error = true;
				$this->error_msg[] = "Paymazon payment-status and order-id from database cannot be empty (Query Data).";
			} else {
				switch (strtoupper($query_params['transaction_data']['paymazon_payment_status'])) {
					case 'FAILED':
					case 'CANCELED':
					case 'COMPLETED':
					case 'SUCCESS':
						if (isset($query_params['input']['payment_result_status'])) {
							if ($query_params['input']['payment_result_status'] !== $query_params['transaction_data']['paymazon_payment_status']) {
								$query_params['new_payment_status'] = TRUE;
							} else {
								$query_params['new_payment_status'] = FALSE;
							}
						}
					break;
					case 'PROCESSING':
					case 'WAITING':
					default:
						if (isset($query_params['input']['payment_result_status'])) {
							if ($query_params['input']['payment_result_status'] === $query_params['transaction_data']['paymazon_payment_status']) {
								$query_params['new_payment_status'] = FALSE;
							} else {
								$query_params['new_payment_status'] = TRUE;
							}
						}
					break;
				}
			}
		}
		if (!$this->error) {
			$query_params['order_data'] = $this->model_checkout_order->getOrder($query_params['transaction_data']['order_id']);
			$query_params['encrypt_params'] = array(
				'request_id'					=> (isset($query_params['transaction_data']['request_id']) ? $query_params['transaction_data']['request_id'] : ''),
				'payment_id'					=> (isset($query_params['transaction_data']['paymazon_payment_id']) ? $query_params['transaction_data']['paymazon_payment_id'] : ''),
				'payment_type'					=> (isset($query_params['input']['payment_type']) ? $query_params['input']['payment_type'] : ''),
				'payment_method'				=> (isset($query_params['transaction_data']['paymazon_payment_code']) ? $query_params['transaction_data']['paymazon_payment_code'] : ''),
			);
			try {
				$query_params['encrypt'] = $Paymazon->get_encrypt_string('identify', $query_params['encrypt_params']);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Cannot create internal encrypt for validation: {$ex->getMessage()}";
			}
		}
		if (!$this->error) {
			if (!isset($query_params['encrypt']['hash'])) {
				$this->error = true;
				$this->error_msg[] = "Encrypt params not have hash";
			} else {
				// Check Encrypt for Validation
				if ($query_params['input']['payment_encrypt'] !== $query_params['encrypt']['hash']) {
					$this->error = true;
					$this->error_msg[] = "Encrypt string is not match";
				}
			}
		}
		//------------------------------------------------------------------------------------------------
		// Update order-payment status on Opencart
		//------------------------------------------------------------------------------------------------
		if (!$this->error) {
			if ($query_params['new_payment_status'] === TRUE) {
				$query_params['payment_status'] = $this->getPaymentStatus($query_params['input']['payment_result_status']);
				//----
				if ((int)$query_params['payment_status'] != 1) {
					$query_params['update_params'] = array(
						'paymazon_payment_status'			=> $query_params['input']['payment_result_status'],
					);
					try {
						$query_params['update_payment_result'] = $this->model_extension_payment_paymazonkbankinst41->set_order_data_by('seq', $query_params['transaction_data']['seq'], $query_params['update_params']);
					} catch (Exception $ex) {
						$this->error = true;
						$this->error_msg[] = "Exception error while update payment-result-status";
					}
				}
			} else {
				$query_params['payment_status'] = 1;
			}
		}
		if (!$this->error) {
			if ($query_params['new_payment_status'] === TRUE) {
				if ((int)$query_params['payment_status'] != 1) {
					$this->model_checkout_order->addOrderHistory($query_params['order_data']['order_id'], $query_params['payment_status'], json_encode($query_params['input'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
				}
			}
		}
		//------------------------------------------------------------------------------------------------
		if (!$this->error) {
			echo "Continue";
		} else {
			echo json_encode($this->error_msg, JSON_PRETTY_PRINT);
		}
	}
	
	
	public function paymentredirect() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$this->load->model('checkout/order');
		$this->language->load('extension/payment/paymazon');
		$this->loadPaymazonLib();
		$query_params = array(
			'input'						=> array(),
			'new_payment_status'		=> FALSE,
		);
		//--------------------------------------------------
		$Paymazon = new Paymazon(Paymazon_Config::$CONFIG);
		if (!$this->error) {
			if (!isset(Paymazon_Config::$CONFIG['pg_code'])) {
				$this->error = true;
				$this->error_msg[] = "Module not have pg-code";
			}
		}
		if (!$this->error) {
			try {
				$input_params = $Paymazon->get_php_input_request()['body'];
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Error exception while collectiong php input-params.";
			}
		}
		if (!$this->error) {
			$query_params['input'] = array(
				'payment_id'				=> (isset($input_params['payment_id']) ? $input_params['payment_id'] : ''),
			);
			$query_params['input']['payment_id'] = ((is_numeric($query_params['input']['payment_id'])  || is_string($query_params['input']['payment_id'])) ? sprintf("%s", $query_params['input']['payment_id']) : '');
			try {
				$query_params['wheres'] = array(
					'paymazon_payment_code'		=> $this->config->get('payment_paymazonkbankinst41_pgcode'),
					'paymazon_payment_shopid'	=> $this->config->get('payment_paymazonkbankinst41_shopid'),
					'paymazon_payment_id'		=> $query_params['input']['payment_id'],
				);
				$query_params['transaction_data'] = $this->model_extension_payment_paymazonkbankinst41->get_order_data_by('payment', '', $query_params['wheres']);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Cannot get data of paymazon transaction by payment-id (payment redirect): {$ex->getMessage()}";
			}
		}
		if (!$this->error) {
			if (!isset($query_params['transaction_data']['paymazon_payment_status']) || !isset($query_params['transaction_data']['order_id'])) {
				$this->error = true;
				$this->error_msg[] = "Paymazon payment-status and order-id from database cannot be empty (Record Queried Data).";
				$this->error_msg[] = $query_params;
			} else {
				$query_params['order_data'] = $this->model_checkout_order->getOrder($query_params['transaction_data']['order_id']);
			}
		}
		if (!$this->error) {
			if (isset($query_params['transaction_data']['paymazon_payment_id'])) {
				try {
					$query_params['payment_result'] = $Paymazon->get_payment_result_by_curl('GET', "/result/{$query_params['transaction_data']['paymazon_payment_id']}");
				} catch (Exception $ex) {
					$this->error = true;
					$this->error_msg[] = "Could not get payment result by curl";
				}
			} else {
				$this->error = true;
				$this->error_msg[] = "Transaction data do not have payment-id";
			}
		}
		if (!$this->error) {
			if (!isset($query_params['payment_result']['response']['body'])) {
				$this->error = true;
				$this->error_msg[] = "Error response not have body";
			} else {
				try {
					$query_params['payment_result']['response']['body'] = json_decode($query_params['payment_result']['response']['body'], true);
				} catch (Exception $ex) {
					$this->error = true;
					$this->error_msg[] = "Cannot decoded body response from payment-result instance";
				}
			}
		}
		//-------------------------------------------------------------------------------------------------------------
		if (!$this->error) {
			$query_params['new_payment_status'] = FALSE;
			$query_params['new_payment_result_status'] = "";
			$query_params['order_data'] = $this->model_checkout_order->getOrder($query_params['transaction_data']['order_id']);
			if (isset($query_params['payment_result']['response']['body']['payment_result']['payment_result_status'])) {
				$query_params['new_payment_result_status'] = (is_string($query_params['payment_result']['response']['body']['payment_result']['payment_result_status']) ? strtoupper($query_params['payment_result']['response']['body']['payment_result']['payment_result_status']) : '');
			} else {
				$this->error = true;
				$this->error_msg[] = "There is no payment-result-status from Paymazon API";
			}
		}
		if (!$this->error) {
			switch (strtoupper($query_params['transaction_data']['paymazon_payment_status'])) {
				case 'FAILED':
				case 'CANCELED':
				case 'COMPLETED':
				case 'SUCCESS':
					$query_params['new_payment_status'] = FALSE;
				break;
				case 'PROCESSING':
				case 'WAITING':
				default:
					if ($query_params['new_payment_result_status'] === $query_params['transaction_data']['paymazon_payment_status']) {
						$query_params['new_payment_status'] = FALSE;
					} else {
						$query_params['new_payment_status'] = TRUE;
					}
				break;
			}
			if ($query_params['new_payment_status'] == TRUE) {
				$query_params['payment_status'] = $this->getPaymentStatus($query_params['new_payment_result_status']);
				$query_params['update_params'] = array(
					'paymazon_payment_status'	=> $query_params['new_payment_result_status'],
				);
				try {
					$query_params['update_payment_result'] = $this->model_extension_payment_paymazonkbankinst41->set_order_data_by('seq', $query_params['transaction_data']['seq'], $query_params['update_params']);
				} catch (Exception $ex) {
					$this->error = true;
					$this->error_msg[] = "Exception error while update payment-result-status on redirect page: {$ex->getMessage()}";
				}
			}
		}
		if (!$this->error) {
			if ($query_params['new_payment_status'] == TRUE) {
				$this->model_checkout_order->addOrderHistory($query_params['order_data']['order_id'], $query_params['payment_status'], json_encode($query_params['payment_result']['response']['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			}
		}
		//-------------------------------------------------------------------------------------------------------------
		if (!$this->error) {
			if (isset($this->session->data['order_id'])) {
				switch (strtoupper($query_params['new_payment_result_status'])) {
					case 'COMPLETED':
					case 'SUCCESS':
						$this->response->redirect($this->url->link('checkout/success', '', true));
					break;
					case 'CANCELED':
					case 'FAILED':
						$this->response->redirect($this->url->link('checkout/failure', '', true));
					break;
					case 'WAITING':
					case 'PROCESSING':
					default:
						$this->response->redirect($this->url->link('checkout/checkout', '', true));
					break;
				}
			} else {
				$this->response->redirect($this->url->link('account/login', '', true));
			}
		} else {
			$this->response->setOutput(json_encode($this->error_msg));
		}
	}
	public function paymentfailed() {
		$this->load->model('checkout/order');
		if (isset($this->session->data['order_id'])) {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 7, 'Cancel from paymazon close.');
		} else {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	}
	public function paymentcanceled() {
		$this->load->language('extension/payment/paymazonkbankinst41');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_failure'] = $this->language->get('text_failure');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['checkout_url'] = $this->url->link('checkout/cart');
		$this->response->setOutput($this->load->view('extension/payment/paymazonkbankinst41_failed', $data));
	}
	//===============================================================================================================
	public function index() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$collectData = array(
			'collect'			=> array(),
		);
		$collectData['collect']['order_data'] = $this->getOrderData();
		$collectData['collect']['Paymazon'] = new Paymazon(Paymazon_Config::$CONFIG);
		if (!$this->error) {
			if (!isset(Paymazon_Config::$CONFIG['pg_code'])) {
				$this->error = true;
				$this->error_msg[] = "Module not have pg-code";
			}
		}
		if (!$this->error) {
			try {
				$collectData['collect']['product_data'] = $this->cart->getProducts();
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Exception error while get product-data from opencart class: {$ex->getMessage()}";
			}
		}
		$collectData['pg_display_name'] = $this->config->get('payment_paymazonkbankinst41_display_name');
		$collectData['action'] = $this->url->link('extension/payment/paymazonkbankinst41/smartpay', '', 'SSL');
		$collectData['getinstallmenttenors'] = $this->url->link('extension/payment/paymazonkbankinst41/getinstallmenttenors', '', 'SSL');
		$collectData['installment_data'] = array();
		$collectData['shopids'] = $this->config->get('payment_paymazonkbankinst41_installment_shopids');
		$collectData['shopid'] = $this->config->get('payment_paymazonkbankinst41_shopid');
		if (is_array($collectData['shopids']) && (count($collectData['shopids']) > 0)) {
			foreach ($collectData['shopids'] as &$keval) {
				$keval['tenors'] = array();
				if (isset($keval['value']) && isset($keval['terms'])) {
					$shopid_value = $keval['value'];
					$collectData['installment_data'][$shopid_value] = array();
					if (is_array($keval['terms']) && count($keval['terms'])) {
						foreach ($keval['terms'] as $val) {
							if (isset($val['value'])) {
								$keval['tenors'][] = $val['value'];
								$collectData['installment_data'][$shopid_value][] = array('value' => $val['value'], 'text' => $val['text']);
							}
						}
					}
				}
			}
		}
		$collectData['installment_tenors'] = $this->model_extension_payment_paymazonkbankinst41->getInstallmentTenors();
		$collectData['installment_json'] = json_encode($collectData['installment_data'], JSON_UNESCAPED_SLASHES);
		$collectData['installment_loop'] = (isset($collectData['shopids'][0]['value']) ? $collectData['shopids'][0]['value'] : 0);
		/*
		echo "<pre>";
		if (!$this->error) {
			print_r($collectData);
		} else {
			print_r($this->error_msg);
		}
		exit;
		*/
		$collectData['entry_installment'] = "Select Shop and Installment Terms";
		$collectData['entry_tenor'] = "Select Installment Months";
		
		
		return $this->load->view('extension/payment/paymazonkbankinst41_smartpay', $collectData);
	}
	public function getinstallmenttenors() {
		$this->load->model('extension/payment/paymazonkbankinst41');
		$installment_shopid = (isset($this->request->post['installment_shopid']) ? $this->request->post['installment_shopid'] : 0);
		$installment_data = $this->config->get('payment_paymazonkbankinst41_installment_shopids');
		$installment_string = "";
		if (isset($installment_data)) {
			if (is_array($installment_data) && (count($installment_data) > 0)) {
				foreach ($installment_data as $key => $keval) {
					if (isset($keval['value']) && isset($keval['terms'])) {
						if ($keval['value'] == $installment_shopid) {
							if (is_array($keval['terms']) && count($keval['terms'])) {
								foreach ($keval['terms'] as $val) {
									if (isset($val['value']) && isset($val['text'])) {
										$installment_string .= "<option value='{$val['value']}'>{$val['text']}</option>";
									}
								}
							}
						}
					}
				}
			}
		}
		echo $installment_string;
	}
	public function smartpay() {
		$order_data = $this->getOrderData();
		$Paymazon = new Paymazon(Paymazon_Config::$CONFIG);
		if (!$this->error) {
			if (!isset(Paymazon_Config::$CONFIG['pg_code'])) {
				$this->error = true;
				$this->error_msg[] = "Module not have pg-code";
			}
		}
		if (!$this->error) {
			$product_data = $this->cart->getProducts();
			foreach ($product_data as &$keval) {
				$keval['total_with_tax'] = $this->tax->calculate($keval['total'], $keval['tax_class_id'], TRUE);
				//$keval['total_with_tax_default'] = $this->tax->calculate($keval['total'], $keval['tax_class_id'], $this->config->get('config_tax'));
			}
			$custom_params = array(
				'url'		=> array(
					'notify_url'		=> $this->url->link('extension/payment/paymazonkbankinst41/paymentnotify', '', 'SSL'),
					'success_url'		=> $this->url->link('extension/payment/paymazonkbankinst41/paymentredirect', '', 'SSL'),
					'failed_url'		=> $this->url->link('extension/payment/paymazonkbankinst41/paymentfailed', '', 'SSL'),
					'cancel_url'		=> $this->url->link('extension/payment/paymazonkbankinst41/paymentcanceled', '', 'SSL'),
				),
				'custom'	=> array(
					'ref1'				=> $this->config->get('payment_paymazonkbankinst41_custom_field1'),
					'ref2'				=> $this->config->get('payment_paymazonkbankinst41_custom_field2'),
					'ref3'				=> $this->config->get('payment_paymazonkbankinst41_custom_field3'),
				),
			);
			$custom_params['smartpay'] = array(
				'shopid'				=> $this->config->get('payment_paymazonkbankinst41_shopid'),
				'instmonth'				=> (isset($this->request->post['payment_paymazonkbankinst41_installment_instmonth']) ? $this->request->post['payment_paymazonkbankinst41_installment_instmonth'] : ''),
			);
		}
		if (!$this->error) {
			try {
				$order_data['request_id'] = $Paymazon->create_new_request_id("Asia/Bangkok");
				$Paymazon->create_payment_structure('create', Paymazon_Config::$CONFIG['pg_code'], $order_data, $custom_params, $product_data);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Cannot create payment-structure with exception: {$ex->getMessage()}";
			}
		}
		//===================================
		// Debug
		/*
		if (!$this->error) {
			echo "<pre>";
			print_r($product_data);
			print_r($Paymazon);
			echo json_encode($Paymazon, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
		} else {
			print_r($this->error_msg);
		}
		exit;
		*/
		//===================================
		
		
		
		
		
		if (!$this->error) {
			if (!isset($this->session->data['payment_method']['code'])) {
				$this->error = true;
				$this->error_msg[] = "There is no payment-method code from session";
			}
		}
		if (!$this->error) {
			if (strtolower($this->session->data['payment_method']['code']) === 'paymazonkbankinst41') {
				try {
					$Create_Payment = $Paymazon->create_payment_request_by_curl('POST', '/create');
				} catch (Exception $ex) {
					$this->error = true;
					$this->error_msg[] = "Cannot create curl-instance with exception : {$ex->getMessage()}";
				}
			} else {
				$this->error = true;
				$this->error_msg[] = "Payment method code from session is no paymazonkbankinst41";
			}
		}
		if (!$this->error) {
			if (!isset($Create_Payment['response']['body'])) {
				$this->error = true;
				$this->error_msg[] = "Error response not have body";
			}
		}
		if (!$this->error) {
			try {
				$Create_Payment['response']['body'] = json_decode($Create_Payment['response']['body'], true);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Cannot decoded body response from payment-create instance";
			}
		}
		if (!$this->error) {
			if (!isset($Create_Payment['response']['body']['payment_id']) || !isset($Create_Payment['response']['body']['request_id']) || !isset($Create_Payment['response']['body']['response']['payment']) || !isset($Create_Payment['response']['body']['redirect'])) {
				$this->error = true;
				$this->error_msg[] = "response payment, payment-id, request-id, and redirect should be exists from paymazon";
				$this->error_msg[] = $Create_Payment;
			}
		}
		if (!$this->error) {
			$query_orders = array(
				'order_id'			=> (isset($order_data['order_id']) ? $order_data['order_id'] : ''),
				'request_id'		=> (isset($Create_Payment['response']['body']['request_id']) ? $Create_Payment['response']['body']['request_id'] : ''),
			);
			$query_params = array(
				'paymazon_payment_code'		=> $Create_Payment['response']['body']['response']['payment'],
				'paymazon_payment_id'		=> $Create_Payment['response']['body']['payment_id'],
				'paymazon_payment_shopid'	=> $this->config->get('payment_paymazonkbankinstdyn_shopid'),
				'paymazon_payment_status'	=> 'PROCESSING',
			);
			try {
				$Create_Payment['new_paymazon_data_seq'] = $this->model_extension_payment_paymazonkbankinst41->insertNewPaymazonTransaction($query_orders['order_id'], $query_orders['request_id'], $query_params);
			} catch (Exception $ex) {
				$this->error = true;
				$this->error_msg[] = "Error exception while insert new payment to paymazon table: {$ex->getMessage()}";
			}
		}
		if (!$this->error) {
			if ((int)$Create_Payment['new_paymazon_data_seq'] === 0) {
				$this->error = true;
				$this->error_msg[] = "Paymazon data seq cannot be 0";
			}
		}
		if (!$this->error) {
			$collectData = array(
				'errors'			=> false,
				'redirect'			=> $Create_Payment['response']['body']['redirect'],
				'payment_id'		=> $Create_Payment['response']['body']['payment_id'],
				'seq'				=> $Create_Payment['new_paymazon_data_seq'],
				'pay_type'			=> 'paymazonkbankinst41',
				'pg_display_name'	=> $this->config->get('payment_paymazonkbankinst41_display_name'),
			);
		} else {
			$collectData = array(
				'errors'			=> $this->error_msg,
			);
		}
		
		
		if (isset($collectData['redirect'])) {
			header("Location: {$collectData['redirect']}");
			exit;
		} else {
			print_r($collectData);
		}
	}
	
	


	
	
	
}








