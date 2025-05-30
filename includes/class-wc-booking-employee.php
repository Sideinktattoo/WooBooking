<?php
class WC_Booking_Employee {
    public static function init() {
        add_action('wc_booking_employee_add_form_fields', [__CLASS__, 'add_employee_fields']);
        add_action('wc_booking_employee_edit_form_fields', [__CLASS__, 'edit_employee_fields'], 10, 2);
        add_action('created_wc_booking_employee', [__CLASS__, 'save_employee_fields']);
        add_action('edited_wc_booking_employee', [__CLASS__, 'save_employee_fields']);
        add_filter('manage_edit-wc_booking_employee_columns', [__CLASS__, 'employee_columns']);
        add_filter('manage_wc_booking_employee_custom_column', [__CLASS__, 'employee_column_content'], 10, 3);
    }

    public static function add_employee_fields() {
        ?>
        <div class="form-field">
            <label for="employee_email"><?php _e('Email', 'wc-booking'); ?></label>
            <input type="email" name="employee_email" id="employee_email" value="">
            <p class="description"><?php _e('Employee email address', 'wc-booking'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="employee_phone"><?php _e('Phone', 'wc-booking'); ?></label>
            <input type="text" name="employee_phone" id="employee_phone" value="">
            <p class="description"><?php _e('Employee phone number', 'wc-booking'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="employee_specialization"><?php _e('Specialization', 'wc-booking'); ?></label>
            <input type="text" name="employee_specialization" id="employee_specialization" value="">
            <p class="description"><?php _e('Employee specialization or skills', 'wc-booking'); ?></p>
        </div>
        <?php
    }

    public static function edit_employee_fields($term, $taxonomy) {
        $email = get_term_meta($term->term_id, 'employee_email', true);
        $phone = get_term_meta($term->term_id, 'employee_phone', true);
        $specialization = get_term_meta($term->term_id, 'employee_specialization', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="employee_email"><?php _e('Email', 'wc-booking'); ?></label></th>
            <td>
                <input type="email" name="employee_email" id="employee_email" value="<?php echo esc_attr($email); ?>">
                <p class="description"><?php _e('Employee email address', 'wc-booking'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="employee_phone"><?php _e('Phone', 'wc-booking'); ?></label></th>
            <td>
                <input type="text" name="employee_phone" id="employee_phone" value="<?php echo esc_attr($phone); ?>">
                <p class="description"><?php _e('Employee phone number', 'wc-booking'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="employee_specialization"><?php _e('Specialization', 'wc-booking'); ?></label></th>
            <td>
                <input type="text" name="employee_specialization" id="employee_specialization" value="<?php echo esc_attr($specialization); ?>">
                <p class="description"><?php _e('Employee specialization or skills', 'wc-booking'); ?></p>
            </td>
        </tr>
        <?php
    }

    public static function save_employee_fields($term_id) {
        if (isset($_POST['employee_email'])) {
            update_term_meta($term_id, 'employee_email', sanitize_email($_POST['employee_email']));
        }
        
        if (isset($_POST['employee_phone'])) {
            update_term_meta($term_id, 'employee_phone', sanitize_text_field($_POST['employee_phone']));
        }
        
        if (isset($_POST['employee_specialization'])) {
            update_term_meta($term_id, 'employee_specialization', sanitize_text_field($_POST['employee_specialization']));
        }
    }

    public static function employee_columns($columns) {
        $columns['email'] = __('Email', 'wc-booking');
        $columns['phone'] = __('Phone', 'wc-booking');
        $columns['specialization'] = __('Specialization', 'wc-booking');
        return $columns;
    }

    public static function employee_column_content($content, $column_name, $term_id) {
        switch ($column_name) {
            case 'email':
                $content = get_term_meta($term_id, 'employee_email', true);
                break;
                
            case 'phone':
                $content = get_term_meta($term_id, 'employee_phone', true);
                break;
                
            case 'specialization':
                $content = get_term_meta($term_id, 'employee_specialization', true);
                break;
        }
        
        return $content;
    }
}
