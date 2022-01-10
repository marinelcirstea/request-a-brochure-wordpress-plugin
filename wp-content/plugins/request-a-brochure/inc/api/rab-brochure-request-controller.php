<?php
class RAB_Brochure_Request_Controller
{
    private $rs;
    function __construct()
    {
        require_once('rab-service.php');
        $this->rs = new RAB_Service();
    }

    public function get_brochure_requests()
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }

        $brochures = $this->rs->get_all_brochure_requests();
        if (!$brochures) {
            return new WP_REST_Response([], 404);
        }

        return new WP_REST_Response($brochures, 200);
    }
    public function verify_recaptcha($token)
    {

        // send the token to google
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        // set the post data
        $post_data = array(
            'secret' => SECRET_KEY,
            'response' => $token,
        );
        // set the post options
        $post_options = array(
            'http' => array(
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query($post_data)
            )
        );
        // create a stream
        $context = stream_context_create($post_options);
        // get the response
        $response = file_get_contents($url, false, $context);
        // decode the json
        $json_response = json_decode($response, true);
        // return the response
        return new WP_REST_Response($json_response, 200);
    }

    public function create_brochure_request(string $name, string $address, array $brochures)
    {
        $request = $this->rs->create_brochure_request($name, $address, $brochures);
        if (!$request) {
            return new WP_REST_Response([
                'message' => 'Request already exists'
            ], 400);
        }

        return new WP_REST_Response($request, 200);
    }

    public function delete_brochure_request(string $request_id)
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }

        $res = $this->rs->delete_brochure_request($request_id);
        if (!$res) {
            return new WP_REST_Response($res, 500);
        }
        return new WP_REST_Response($res);
    }

    public function update_brochure_request_status(string $request_id, string $status)
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }
        
        $res = $this->rs->update_brochure_request_status($request_id, $status);
        if (!$res) {
            return new WP_REST_Response($res, 500);
        }
        return new WP_REST_Response($res);
    }
}
