<?php
require_once('inc/user_auth.php');
require_once('inc/functions.php');

if (!empty($_POST['action']))
{
    $redirect = SITE_ROOT;

    switch($_POST['action'])
    {
        case 'add_business':
            $data = get_business_post_fields();
            if (!empty($data['fields']) && !empty($data['categories']))
            {
                add_business($data['fields'], $data['categories']);
            }
            else
            {
                set_session_msg('error', 'Adding failed: fields are missing.');
            }
            break;
        case 'update_business':
            $data = get_business_post_fields();
            if (!empty($data['fields']) && !empty($data['categories']) && !empty($_POST['business_id']))
            {
                update_business($_POST['business_id'], $data['fields'], $data['categories']);
            }
            else
            {
                set_session_msg('error', 'Updating failed: fields are missing.');
            }
            break;
        case 'delete_business':
            if (!empty($_POST['business_id']))
            {
                $business = get_business($_POST['business_id']);
                $business_info = (!empty($business['info'])) ? $business['info'] : '';
                delete_business($_POST['business_id']);
                if (!empty($business_info))
                {
                    set_session_msg('success', '"'.$business_info->name.'" deleted.');
                }
                else
                {
                    set_session_msg('success', 'Business deleted.');
                }
            }
            // this is called through AJAX, so we need to exit before redirect happens
            die();
            break;
        case 'add_category':
            $data = get_category_post_fields();
            if (!empty($data['name']))
            {
                add_category($data);
            }
            else
            {
                set_session_msg('error', 'Adding failed: fields are missing.');
            }
            $redirect = "manage-categories.php";
            break;
        case 'update_category':
            $data = get_category_post_fields();
            if (!empty($data['name']) && !empty($_POST['category_id']))
            {
                update_category($_POST['category_id'], $data);
            }
            else
            {
                set_session_msg('error', 'Updating failed: fields are missing.');
            }
            $redirect = "manage-categories.php";
            break;
        case 'delete_category':
            if (!empty($_POST['category_id']))
            {
                $category = get_category($_POST['category_id']);
                delete_category($_POST['category_id']);
                if (!empty($category))
                {
                    set_session_msg('success', '"'.$category->name.'" deleted.');
                }
                else
                {
                    set_session_msg('success', 'Business deleted.');
                }
            }
            // this is called through AJAX, so we need to exit before redirect happens
            die();
            break;
        case 'add_user':
            $data = get_user_post_fields('add');
            if (!empty($data['user_name']) && !empty($data['user_email']) && !empty($data['user_pw']) && !empty($data['user_pw_repeat']))
            {
                add_user($data);
            }
            else
            {
                set_session_msg('error', 'Adding failed: fields are missing.');
            }
            $redirect = "manage-users.php";
            break;
        case 'update_user':
            $data = get_user_post_fields('update');
            if (!empty($data['user_name']) && !empty($data['user_email']) && !empty($_POST['user_id']))
            {
                update_user($_POST['user_id'], $data);
            }
            else
            {
                set_session_msg('error', 'Updating failed: fields are missing.');
            }
            $redirect = "manage-users.php";
            break;
        case 'delete_user':
            if (!empty($_POST['user_id']))
            {
                $user = get_user($_POST['user_id']);
                delete_user($_POST['user_id']);
                if (!empty($user))
                {
                    set_session_msg('success', '"'.$user->user_name.'" deleted.');
                }
                else
                {
                    set_session_msg('success', 'User deleted.');
                }
            }
            else
            {
                set_session_msg('error', 'Delete failed: missing user ID.');
            }
            // this is called through AJAX, so we need to exit before redirect happens
            die();
            break;
        default:

    }
}

header('Location: '.$redirect);