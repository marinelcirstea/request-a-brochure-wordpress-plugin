<?php
function rab_admin_page_html()
{
    echo '<h1>Request a brochure - Admin</h1>';
?>
    <h2>Create a new brochure</h2>
    <input type="text" name="brochure_name" placeholder="Brochure Name">
    <button name="add" class="button button-primary" id="button-create-brochure">Create new Brochure</button>
    <h2>Current brochures</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Brochure</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="rab-brochures-tbody"></tbody>
    </table>
<?php
}

?>