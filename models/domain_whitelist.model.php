<?php namespace Models;

class DomainWhitelist
{
    public $id;
    public $application_id;
    public $domain;
    public $ip_address;

    public function __construct($data = null)
    {
        if ($data != null) {
            $this->id = $data['id'];
            $this->application_id = $data['application_id'];
            $this->domain = $data['domain'];
            $this->ip_address = $data['ip_address'];
        }
    }
}
