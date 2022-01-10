<?php
function rab_admin_page_html()
{
    echo '<h1>Request a brochure - Admin</h1>';
?>
    <h2>Create a new brochure</h2>
    <form id='rab-form-create-brochure'>
        <input type="text" placeholder="Brochure Name">
        <button type="submit" class="button button-primary">Create new Brochure</button>
    </form>

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
    <br>
    <!-- spinner  -->
    <div class="rab-spinner">
        <h1>
            Loading...
        </h1>
    </div>


    <div style="display: flex; flex-direction:row;align-items:center; column-gap:25px;">
        <h2>Brochure requests</h2>
        <button id="rab-button-refresh-requests" class="button button-primary">Refresh</button>
    </div>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Brochures</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="rab-brochure-requests-tbody"></tbody>
    </table>
    <br>
    <!-- spinner  -->
    <div class="rab-requests-spinner">
        <h1>
            Loading...
        </h1>
    </div>
<?php
}

?>