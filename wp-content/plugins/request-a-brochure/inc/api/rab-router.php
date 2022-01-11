<?php

class RAB_Router
{
    private $rbc;
    private $rbrc;

    function __construct()
    {
        require_once('rab-brochure-controller.php');
        $this->rbc = new RAB_Brochure_Controller();

        require_once('rab-brochure-request-controller.php');
        $this->rbrc = new RAB_Brochure_Request_Controller();

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
                        $json = $req->get_json_params();
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
                        $json = $req->get_json_params();
                        $params = $req->get_url_params();

                        return $this->rbc->update_brochure_status((int)$params['id'], (int)$json['active']);
                    }
                ],
            ],

            // BROCHURE REQUESTS
            'brochure-recaptcha' => [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => function ($req) {
                        $json = $req->get_json_params();
                        // return new WP_REST_Response(['token'=>$json['token']], 200);
                        return $this->rbrc->verify_recaptcha($json['token']);
                    },
                ],
            ],
            'brochure-requests' => [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => function () {
                        return $this->rbrc->get_brochure_requests();
                    }
                ],
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => function ($req) {
                        $json = $req->get_json_params();

                        return $this->rbrc->create_brochure_request($json['name'], $json['email'], $json['brochures']);
                    },
                ]
            ],
            'brochure-requests/(?P<request_id>[\w]+)' => [
                [
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => function ($req) {
                        $params = $req->get_url_params();
                        return $this->rbrc->delete_brochure_request($params['request_id']);
                    }
                ],
                [
                    'methods' => WP_REST_Server::EDITABLE,
                    'callback' => function ($req) {
                        $json = $req->get_json_params();
                        $params = $req->get_url_params();

                        return $this->rbrc->update_brochure_request_status($params['request_id'], $json['status']);
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
