<?php

class TalonOne {
    public $applicationId;
    public $applicationKey;
    public $subdomain;
    
    public function apiRequest($method, $resource, $payload) {
        $baseUrl = "https://".$this->subdomain.".talon.one/v1";
        $key = hex2bin($this->applicationKey);
        $jsonString = json_encode($payload);
        $signature = hash_hmac('md5', $jsonString, $key);
        $url = $baseUrl.'/'.$resource;

        $headers = array(
            'Content-Type: application/json',
            'Content-Signature: signer='.$this->applicationId.'; signature='.$signature
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonString);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);

        curl_close($curl);
        
        if ($response) {
            $response = json_decode($response, true);
        }
        return $response;
    }

    public function post($resource, $payload) {
        return $this->apiRequest("POST", $resource, $payload);
    }
    
    public function put($resource, $payload) {
        return $this->apiRequest("PUT", $resource, $payload);
    }

    public function processEffects($response, $handlers) {
        $fxs = $response['event']['effects'];
        
        foreach ($fxs as $fx) {
            list($campaignId, $rulesetId, $ruleIndex, $effect) = $fx;
            $action = $effect[0];
            $args = array_slice($effect, 1);

            $handler = $handlers[$action];
            if ($handler) {
                $handler($response, $args);
            }
        }
    }
}

?>