<?php

class TaloneOneManagement {
    public $subdomain;
    public $sessionToken;
    
    public function apiRequest($method, $resource, $payload) {
        $baseUrl = "https://".$this->subdomain.".talon.one/v1";
        $jsonString = json_encode($payload);
        $url = $baseUrl.'/'.$resource;

        $headers = array(
            'Content-Type: application/json'
        );

        if ($this->sessionToken) {
            array_push($headers, "Authorization: Bearer ".$this->sessionToken);
        }

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
    
    public function get($resource) {
        return $this->apiRequest("GET", $resource, array());
    }
    
    public function delete($resource, $payload) {
        return $this->apiRequest("DELETE", $resource, $payload);
    }
    
    public function createManagementSession($email, $password) {
        $response = $this->post("sessions", array("email" => $email, "password" => $password));
        $this->sessionToken = $response['token'];
        return $response;
    }

    public function destroyManagementSession() {
        $response = $this->delete("sessions", array());
        $this->sessionToken = NULL;
        return $response;
    }
}

?>