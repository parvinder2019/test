<?php include("estash_aggregator.php"); ?>
<?php 
    $agg_req = new aggregator_request();
    $agg_req->set_url("http://41.60.195.195:8989/estash/aggregator");
    $agg_req->set_credentials("Pareza","Pareza","ECABAC63CA67931CA14654A2B2CA277C");
    $agg_req->set_provider_id("4001");
    $agg_req->set_transaction_type("REVEND"); /* can be PURCHASE, ENQUIRY or REVEND */
    $agg_req->set_customer_account("04223372261");
    $agg_req->set_transaction_amount(10);
    
    
    $agg_req->set_customer_reference("3zwq3rd9wesh4");
    
    $agg_req->set_request_id("3zwq3rd9wesh4");
    
    
    // it will be same custom reference and request id..
    
    
    // receiptnumber. token. 

    /* if you want to see the outgoing message, uncommend these two lines and the outgoing
       message will be returned */
    $outgoing_message = $agg_req->build_message();
    
    $response = $agg_req->send_message();
    echo($response);

?>