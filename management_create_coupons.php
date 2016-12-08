<?php

require "TalonOneManagement.php";

function talonOneMgmtDemo() {
    $t = new TaloneOneManagement();

    // Fill in the details of your Talon.One Application and Campaign here
    // (Available in developer section in Campaign Manager)
    $t->subdomain = "demo";
    $appId = 1;
    $campaignId = 123;
    
    if ($t->createManagementSession("demo@talon.one","demo1234")) {
        // Create some fresh coupons
        // See http://developers.talon.one/management-api/reference/#createCoupons
        $couponAttrs = array(
            'usageLimit' => 1,
            'numberOfCoupons' => 10,
            'couponPattern' => 'DEAL-####-####',
            'validCharacters' => ["1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z"]
        );

        $newCoupons = $t->post("applications/$appId/campaigns/$campaignId/coupons", $couponAttrs);
        foreach ($newCoupons['data'] as $coupon) {
            $couponCode = $coupon['value'];
            echo "New Coupon: $couponCode\n\n";
        }
        
        // Get a list of usable and valid coupons
        $usableCoupons = $t->get("applications/$appId/campaigns/$campaignId/coupons?usable=true&valid=validNow");
        echo "Total usable and valid coupons: ".$usableCoupons['totalResultSize']."\n";
        
        $t->destroyManagementSession();
    }
}

talonOneMgmtDemo();

?>