<?php 
	class aggregator_request {

        var $message_content;
        var $url;
        var $request_id;

        var $terminal_id;
        var $merchant_id;
        var $access_key;

        var $tran_type;
        var $tran_value;

        var $provider_id;
        var $customer_reference;
        var $customer_account;
        var $action;

        function set_message_content($msg_content) {
			$this->message_content = $msg_content;
        }
        
		function get_name() {
			return $this->message_content;
        }

        function set_url($lvurl) {
			$this->url = $lvurl;
        }
        
		function get_url() {
			return $this->url;
        }
        
        function send_message() {
            $ch = curl_init();
            $this->build_message();
            $lv_url = $this->url;
            $lv_xml = $this->message_content;
            curl_setopt($ch, CURLOPT_URL, $lv_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $lv_xml);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                'Content-type: text/xml', 
                                                'Content-length: ' . strlen($lv_xml),
                                                'Expect:'
                                                ));
            $output = curl_exec($ch);
            curl_close($ch);

            return $this->cleanup_string($output);
        }

        function set_credentials($lv_merchant_id, $lv_terminal_id, $lv_access_key) {
            $this->terminal_id = $lv_terminal_id;
            $this->access_key = $lv_access_key;
            $this->merchant_id = $lv_merchant_id;
        }

        function set_transaction_type($lv_transaction_type){
            if ($lv_transaction_type == "BALANCE") {
                $this->action = "0";
                $this->transaction_type = "BALANCE";
            } elseif ($lv_transaction_type == "ENQUIRY")  {
                $this->action = "1";
                $this->transaction_type = "ENQUIRY";
            } elseif ($lv_transaction_type == "PURCHASE")  {
                $this->action = "2";
                $this->transaction_type = "PURCHASE";
            } elseif ($lv_transaction_type == "REVEND")  {
                $this->action = "3";
                $this->transaction_type = "REVEND";
            }else {
                $this->action = "0";
                $this->transaction_type = "BALANCE";
            }
        }

        function set_transaction_amount($lv_tran_value) {
            $this->tran_value = $lv_tran_value;
        }

        function set_provider_id($lv_provider_id){
            $this->provider_id = $lv_provider_id;
        }

        function set_customer_reference($lv_cust_ref){
            $this->customer_reference = $lv_cust_ref;
        }

        function set_customer_account($lv_cust_acc){
            $this->customer_account = $lv_cust_acc;
        }

        function set_request_id($lv_req_id){
            $this->request_id = $lv_req_id;
        }

        function build_message() {
            $lv_msg_string = '<?xml version="1.0" encoding="ISO-8859-1"?>
            <aggregatorrequest version="1.0" requestid="' . $this->request_id . '">
            <credentials>
                <terminalid>' . $this->terminal_id . '</terminalid>
                <accesskey>' . $this->access_key . '</accesskey>
            </credentials>
            <trace>
                <ipaddress>10.11.23.11</ipaddress>
                <senddate>27-03-2019</senddate>
                <sendtime>18:16:37</sendtime>
            </trace>
            <transaction type="'. $this->transaction_type .'">
            <merchant>
                <merchantid>'. $this->merchant_id .'</merchantid>
            </merchant>';

            if ($this->transaction_type == "PURCHASE") {
                $lv_msg_string = $lv_msg_string . '
                <amounts>
                <transactionvalue>'. $this->tran_value .'</transactionvalue>
            </amounts>';
            }

            $lv_msg_string = $lv_msg_string . '
            <provider>
                <providerid>'. $this->provider_id .'</providerid>
                <action>'. $this->action .'</action>
                <customeraccount>'. $this->customer_account .'</customeraccount>
                <customerreference>'. $this->customer_reference .'</customerreference>
            </provider>
            <notification>
                <customersms>0</customersms>
                <customeremail>0</customeremail>
                <providersms>0</providersms>
                <provideremail>0</provideremail>
                <merchantsms>0</merchantsms>
                <merchantmobileno>0</merchantmobileno>
                <merchantemail>0</merchantemail>
            </notification>
            </transaction>
            </aggregatorrequest>';
            $this->set_message_content($lv_msg_string);
            return $lv_msg_string;
            
        }

        function cleanup_string($dirty_string){
            $clean_string = $dirty_string;

            if (substr($clean_string, -1) != ">") {
                $str_len = strlen($clean_string) - 1;
                $clean_string = substr($clean_string, 0, $str_len);
            }
            
            return $clean_string;
        }
 
	}
?>