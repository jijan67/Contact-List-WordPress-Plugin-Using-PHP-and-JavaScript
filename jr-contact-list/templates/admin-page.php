<?php
// Admin page template
?>
<div class="wrap">
    <h1>Contact List</h1>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="<?php echo esc_url(home_url('/sign-up')); ?>" class="page-title-action">Sign Up</a>
    </div>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_list';
    $contacts = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    ?>

    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Hobbies</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td><?php echo esc_html($contact['name']); ?></td>
                    <td><?php echo esc_html($contact['address']); ?></td>
                    <td><?php echo esc_html($contact['phone']); ?></td>
                    <td><?php echo esc_html($contact['email']); ?></td>
                    <td><?php echo esc_html($contact['hobbies']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
