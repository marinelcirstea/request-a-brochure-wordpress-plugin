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
        return wp_send_json_success($brochures);
    }

    public function create_brochure(string $brochure)
    {
        $brochure = $this->rs->create_brochure($brochure);
        return wp_send_json_success($brochure);
    }
}
