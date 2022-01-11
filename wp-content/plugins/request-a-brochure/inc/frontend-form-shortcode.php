<?php

function frontend_form_shortcode()
{
    require_once('api/rab-service.php');
    $rs = new RAB_Service();
    $brochures = $rs->get_active_brochures();
    $html = '<div>
        <style>
            .error-message {
                color: red;
            }
        </style>
        <form id="rab-form">
            <label for="name">Your name</label>
            <br>
            <input type="text" name="name" placeholder="Enter your name" />
            <br>
            <label for="email">Your email address</label>
            <br>
            <input type="text" name="email" placeholder="Enter your email address" />
            <br>
            <p>Brochures:</p>
            <br>';
            foreach ($brochures as $brochure) {
                $html.='<div>
                    <input type="checkbox" name="'. $brochure->brochure.'" data-brochure_id="'.$brochure->id.'">
                    <label for="'.$brochure->brochure.'">'.$brochure->brochure.'</label>
                </div>';
            }
            $html.='<br>
            <br>
            <button type="submit">Request a brochure</button>
        </form>
    </div>';
    return $html;
}
