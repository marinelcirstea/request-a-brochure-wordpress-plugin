<?php

function frontend_form_shortcode()
{
    require_once('api/rab-service.php');
    $rs = new RAB_Service();
    $brochures = $rs->get_active_brochures();
?>
    <div>
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
            <br>
            <?php
            foreach ($brochures as $brochure) {
            ?>
                <div>
                    <input type="checkbox" name="<?php echo $brochure->brochure; ?>" data-brochure_id="<?php echo $brochure->id; ?>">
                    <label for="<?php echo $brochure->brochure; ?>"><?php echo $brochure->brochure; ?></label>
                </div>
            <?php
            }
            ?>
            <br>
            <br>
            <button type="submit">Request a brochure</button>
        </form>
    </div>
<?php
    return '';
}

?>