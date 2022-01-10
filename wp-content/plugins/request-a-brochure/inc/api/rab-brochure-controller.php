<?php
class RAB_Brochure_Controller
{
    private $rs;
    function __construct()
    {
        require_once('rab-service.php');
        $this->rs = new RAB_Service();
    }

    public function get_brochures()
    {
        $brochures = $this->rs->get_all_brochures();
        if (!$brochures) {
            return new WP_REST_Response([], 404);
        }

        return new WP_REST_Response($brochures);
    }

    public function create_brochure(string $brochure)
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }

        $brochure = $this->rs->create_brochure($brochure);
        if (!$brochure) {
            return new WP_REST_Response($brochure, 500);
        }

        return new WP_REST_Response($brochure, 200);
    }

    public function delete_brochure(int $id)
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }

        $res = $this->rs->delete_brochure($id);
        if (!$res) {
            return new WP_REST_Response($res, 500);
        }
        return new WP_REST_Response($res);
    }

    public function update_brochure_status(int $id, int $status)
    {
        if (!IS_ADMIN) {
            return new WP_REST_Response([], 403);
        }
        
        $res = $this->rs->update_brochure_status($id, $status);
        if (!$res) {
            return new WP_REST_Response($res, 500);
        }
        return new WP_REST_Response($res);
    }
}
