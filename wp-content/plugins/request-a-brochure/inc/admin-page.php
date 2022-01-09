<?php

class RAB_AdminPage{
    function __construct(){
        echo '<h1>Request a brochure - Admin</h1>';
        $this->brochure_creation_form();
    }

    function brochure_creation_form(){
        ?>
        <h2>Create a new brochure</h2>
        <form method="POST" action="<?php admin_url('admin.php?page=request-a-brochure') ?>">
            <input type="text" name="brochure_name" placeholder="Brochure Name">
            <button type="submit" name="add" class="button button-primary">Create new Brochure</button>
        </form>
        <?php
    }
}

?>