<?php namespace Models;

class Application
{
    public $id;
    public $name;
    public $description;
    public $app_key;
    public $app_api_slug;

    public function __construct($data = null)
    {
        if ($data != null) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->description = $data['description'];
            $this->app_key = $data['app_key'];
            $this->app_api_slug = $data['app_api_slug'];
        }
    }
}
