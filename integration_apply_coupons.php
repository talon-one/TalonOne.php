<?php

require "TalonOne.php";

function talonOneDemo() {
    $t = new TalonOne();

    // Fill in the details of your Talon.One Application here
    // (Available in developer section in Campaign Manager)
    $t->subdomain = "demo";
    $t->applicationId = 1;
    $t->applicationKey = "fefecafedeadbeef";

    $acceptCoupon = function($response, $args) {
        $coupon = $args[0];
        echo "acceptCoupon $coupon: ".print_r($response,true)."\n";
    };
    
    $rejectCoupon = function($response, $args) {
        $coupon = $args[0];
        echo "rejectCoupon $coupon: ".print_r($response,true)."\n";
    };
    
    $setDiscount = function($response, $args) {
        // This is a good spot to update discount lines in the current cart
        $label = $args[0];
        $value = $args[1];
        echo "setDiscount: $label $value ".print_r($response,true)."\n";
    };

    // Refer to http://developers.talon.one/data-model/attribute-library/ for additional attributes
    $response = $t->put("customer_profiles/demo1234",
                        array('attributes' => array('Email' => 'happycustomer@example.org')));
    if (!$response) {
        echo "Profile update failed. Response: $response\n";
    } else {
        echo "Profile updated.\n";
    }

    $response = $t->put("customer_sessions/testsession12345",
                        array('attributes' => array('BillingCity' => 'Berlin'),
                              'coupon' => 'DEMO-8FM4-WHQ8',
                              'profileId' => 'demo1234',
                              'state' => 'open'));

    if (!$response) {
        echo "Session update failed. Response: $response\n";
    } else {
        // Register all effects you want to handle
        $t->processEffects($response,
                           array('acceptCoupon' => $acceptCoupon,
                                 'rejectCoupon' => $rejectCoupon,
                                 'setDiscount' => $setDiscount));
    }
}

talonOneDemo();

?>