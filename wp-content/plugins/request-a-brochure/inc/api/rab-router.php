<?php

class RAB_Router
{
    private $rbc;

    function __construct()
    {
        require_once('rab-brochure-controller.php');
        $this->rbc = new RAB_Brochure_Controller();
        $this->init_rest();
    }

    function get_api_routes()
    {
        $api_routes = [
            'brochures' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => function () {
                        return $this->rbc->get_brochures();
                    }
                ],
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => function ($req) {
                        $json = json_decode($req->get_json_params(), true);
                        return $this->rbc->create_brochure($json['brochure']);
                    },
                ]
            ],
            'brochures/(?P<id>[\d]+)' => [
                [
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => function ($req) {
                        $params = $req->get_url_params();
                        return $this->rbc->delete_brochure((int)$params['id']);
                    }
                ],
                [
                    'methods' => WP_REST_Server::EDITABLE,
                    'callback' => function ($req) {
                        $json = json_decode($req->get_json_params(), true);
                        $params = $req->get_url_params();

                        return $this->rbc->update_brochure_status((int)$params['id'], (int)$json['active']);
                    }
                ],
            ]
        ];

        return $api_routes;
    }

    function init_rest()
    {
        add_action('rest_api_init', function () {
            $api_routes = $this->get_api_routes();
            foreach ($api_routes as $route => $config) {
                $this->generate_api_route($route, $config);
            }
        });
    }

    function generate_api_route($route, $config)
    {
        register_rest_route('rab/v1', '/' . $route, $config);
    }
}
