<?php

class RAB_Router{
    private $rbc;

    function __construct()
    {
        require_once('rab-brochure-controller.php');
        $this->rbc = new RAB_Brochure_Controller();
        $this->init_rest();
    }

    function get_api_routes(){
        $rbc = $this->rbc;
        $api_routes = [
            'brochures' => [
                'methods' => 'GET',
                'callback' => $rbc->get_brochures(),
            ],
        ];

        return $api_routes;
    }

    function init_rest(){
        add_action('rest_api_init', function () {
            $api_routes = $this->get_api_routes();
            foreach ($api_routes as $route => $config) {
                $this->generate_api_route($route, $config['methods'], $config['callback']);
            }
        });
    }

    function generate_api_route($route, $methods, $callback)
    {
        register_rest_route('rab/v1', $route, array(
            'methods' => $methods,
            'callback' => $callback,
        ));
    }
}