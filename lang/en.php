<?php
/**
 * lang/en.php
 * Translation array for English interface of WishVault.
 */
return [
    // General
    'app_name'           => 'WishVault',
    'app_tagline'        => 'Wishlist Management Information System',
    'loading'            => 'Loading...',
    'lang_id'            => 'ID',
    'lang_en'            => 'EN',

    // Navigation & Header
    'nav_home'           => 'Home',
    'nav_add'            => 'Add Wishlist',
    'nav_logout'         => 'Logout',
    'logged_in_as'       => 'Logged in as',

    // Login Page
    'login_title'        => 'Login - WishVault',
    'login_heading'      => 'Welcome Back',
    'login_subheading'   => 'Sign in to your WishVault account',
    'login_username'     => 'Username',
    'login_password'     => 'Password',
    'login_btn'          => 'Login',
    'login_error'        => 'Invalid username or password.',
    'login_placeholder_username' => 'Enter your username',
    'login_placeholder_password' => 'Enter your password',

    // Index Page (Wishlist)
    'index_title'        => 'My Wishlist - WishVault',
    'index_heading'      => 'My Wishlist',
    'index_add_btn'      => '+ Add Item',
    'index_empty'        => 'No wishlist items yet. Start adding one now!',
    'index_summary_total'=> 'Total Items',
    'index_summary_price'=> 'Total Estimated Price',
    'index_summary_achieved' => 'Achieved',

    // Table Headers
    'tbl_no'             => 'No',
    'tbl_item_name'      => 'Item Name',
    'tbl_category'       => 'Category',
    'tbl_price'          => 'Est. Price',
    'tbl_priority'       => 'Priority',
    'tbl_target_date'    => 'Target Date',
    'tbl_status'         => 'Status',
    'tbl_action'         => 'Action',

    // Form (Create & Edit)
    'create_title'       => 'Add Wishlist - WishVault',
    'create_heading'     => 'Add Wishlist Item',
    'edit_title'         => 'Edit Wishlist - WishVault',
    'edit_heading'       => 'Edit Wishlist Item',
    'form_item_name'     => 'Item Name',
    'form_category'      => 'Category',
    'form_description'   => 'Description',
    'form_price'         => 'Estimated Price (Rp)',
    'form_priority'      => 'Priority',
    'form_target_date'   => 'Target Date (Optional)',
    'form_status'        => 'Status',
    'form_save'          => 'Save',
    'form_update'        => 'Update',
    'form_cancel'        => 'Cancel',
    'form_select'        => '-- Select --',
    'form_placeholder_name'  => 'Example: Gaming Laptop',
    'form_placeholder_desc'  => 'Short description about this item...',
    'form_placeholder_price' => 'Example: 15000000',

    // Priority (display labels; DB stores Indonesian values)
    'priority_rendah'    => 'Low',
    'priority_sedang'    => 'Medium',
    'priority_tinggi'    => 'High',

    // Status (display labels)
    'status_belum'       => 'Not Achieved',
    'status_tercapai'    => 'Achieved',

    // Category (display labels)
    'cat_buku'           => 'Books',
    'cat_elektronik'     => 'Electronics',
    'cat_fashion'        => 'Fashion',
    'cat_gaming'         => 'Gaming',
    'cat_hobi'           => 'Hobbies',
    'cat_kendaraan'      => 'Vehicles',
    'cat_lainnya'        => 'Others',
    'cat_perjalanan'     => 'Travel',

    // Success Messages
    'msg_created'        => 'Wishlist item added successfully!',
    'msg_updated'        => 'Wishlist item updated successfully!',
    'msg_deleted'        => 'Wishlist item deleted successfully!',

    // Error Messages
    'err_not_found'      => 'Item not found or you do not have access.',
    'err_db'             => 'A database error occurred. Please try again.',
    'err_csrf'           => 'Invalid security token. Please try again.',
    'err_invalid_method' => 'Invalid request method.',

    // Form Validation
    'val_name_required'  => 'Item name is required.',
    'val_name_max'       => 'Item name must not exceed 150 characters.',
    'val_cat_required'   => 'Category is required.',
    'val_cat_invalid'    => 'The selected category is not valid.',
    'val_price_required' => 'Estimated price is required.',
    'val_price_numeric'  => 'Estimated price must be a number.',
    'val_price_min'      => 'Estimated price cannot be negative.',
    'val_price_max'      => 'Estimated price is too large (max 9,999,999,999.99).',
    'val_priority_required' => 'Priority is required.',
    'val_priority_invalid'  => 'Invalid priority value.',
    'val_status_required'   => 'Status is required.',
    'val_status_invalid'    => 'Invalid status value.',
    'val_date_invalid'   => 'Invalid date format. Please use YYYY-MM-DD.',
    'val_desc_max'       => 'Description must not exceed 1000 characters.',

    // Delete Confirmation
    'delete_confirm'     => 'Are you sure you want to delete this item?',
    'btn_delete'         => 'Delete',
    'btn_edit'           => 'Edit',

    // Footer
    'footer_text'        => '&copy; 2026 WishVault. Built for academic purposes.',
];
