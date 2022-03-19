<?php

require_once('classes/Database.php');

/**
 * Create page title based on filename.
 */
function get_title( $filename )
{
    switch( $filename )
    {
        case 'index.php':
            return 'Manage Businesses | CRRD';
            break;
        case 'add-business.php':
            return 'Add Business | CRRD';
            break;
        case 'update-business.php':
            return 'Update Business | CRRD';
            break;
        case 'manage-categories.php':
            return 'Manage Categories | CRRD';
            break;
        case 'add-category.php':
            return 'Add Category | CRRD';
            break;
        case 'update-category.php':
            return 'Update Category | CRRD';
            break;
        case 'manage-users.php':
            return 'Manage Users | CRRD';
            break;
        default:
            return 'Management Portal | CRRD';
    }
}

function get_business_post_fields()
{
    $business_name = (!empty($_POST['business_name'])) ? trim($_POST['business_name']) : '';
    $phone = (!empty($_POST['phone'])) ? trim($_POST['phone']) : '';
    $website = (!empty($_POST['website'])) ? trim($_POST['website']) : '';
    $address = (!empty($_POST['address'])) ? trim($_POST['address']) : '';
    $address2 = (!empty($_POST['address2'])) ? trim($_POST['address2']) : '';
    $city = (!empty($_POST['city'])) ? trim($_POST['city']) : '';
    $state = (!empty($_POST['state'])) ? trim($_POST['state']) : '';
    $zip = (!empty($_POST['zip'])) ? trim($_POST['zip']) : '';
    $hours = (!empty($_POST['hours'])) ? trim($_POST['hours']) : '';
    $reuse = (!empty($_POST['reuse'])) ? $_POST['reuse'] : '';
    if ($reuse != '')
    {
        $reuse = 1;
    }
    $repair = (!empty($_POST['repair'])) ? $_POST['repair'] : '';
    if ($repair != '')
    {
        $repair = 1;
    }
    $notes = (!empty($_POST['notes'])) ? trim($_POST['notes']) : '';

    $categories = (!empty($_POST['category'])) ? $_POST['category'] : '';

    $fields = array(
        'name' => $business_name,
        'phone' => $phone,
        'website' => $website,
        'address' => $address,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'hours' => $hours,
        'reuse' => $reuse,
        'repair' => $repair,
        'notes' => $notes,
    );

    $results['fields'] = $fields;
    $results['categories'] = $categories;

    return $results;
}

function get_businesses()
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".BUSINESS_TABLE." b
        ORDER BY b.name
    ");

    $query->execute();

    $businesses = array();
    $i = 0;
    while ($result = $query->fetchObject())
    {
        $businesses[$i]['info'] = $result;
        $businesses[$i]['categories'] = get_business_categories($result->id);
        $i++;
    }

    return $businesses;
}

function get_business($id)
{
    global $db;

    $business = array();

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".BUSINESS_TABLE." b
        WHERE b.id = :id
        LIMIT 1
    ");

    $query->execute(array('id' => $id));

    $business['info'] = $query->fetchObject();

    $business['categories'] = get_business_categories($id);

    return $business;
}

function get_business_details($id)
{
    global $db;

    $business = array();

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".BUSINESS_TABLE." b
        WHERE b.id = :id
        LIMIT 1
    ");

    $query->execute(array('id' => $id));

    return $query->fetchObject();
}

function add_business($fields, $categories)
{
    global $db;
    $new_id = $db->insert(BUSINESS_TABLE, $fields);
    if ($new_id)
    {
        save_geocode_info($new_id, $fields);
        add_cat_relationships($new_id, $categories);
        set_session_msg('success', '"'.$fields['name'].'" added.');
        return true;
    }
    else
    {
        set_session_msg('error', 'Error adding business: database error.');
        return false;
    }
}

function update_business($id, $fields, $categories)
{
    global $db;
    $success = $db->update(BUSINESS_TABLE, $fields, 'id', $id);
    if ($success)
    {
        save_geocode_info($id, $fields);
        delete_cat_relationships($id);
        add_cat_relationships($id, $categories);
        set_session_msg('success', '"'.$fields['name'].'" updated.');
    }
    else
    {
        set_session_msg('error', 'Error updating business: database error.');
        return false;
    }
}

function delete_business($id)
{
    global $db;
    $db->delete(BUSINESS_TABLE, 'id', $id);
}

function save_geocode_info($id, $fields)
{
    global $db;

    $base_url = 'http://dev.virtualearth.net/REST/v1/Locations';
    $query_string = '?q='. urlencode($fields['address'].' '.$fields['city'].' '.$fields['state'].' '.$fields['zip']);
    $output = '&o=json';
    $key = '&key='.BING_MAPS_KEY;
    $url = $base_url.$query_string.$output.$key;

    // use cURL if possible; otherwise just use file_get_contents
    if (function_exists('curl_version'))
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);
        curl_close($ch);
    }
    else
    {
        $json = file_get_contents($url);
    }

    $results = json_decode($json);
    $lat = $results->resourceSets[0]->resources[0]->geocodePoints[0]->coordinates[0];
    $long = $results->resourceSets[0]->resources[0]->geocodePoints[0]->coordinates[1];

    $geocode_fields = array(
        'latitude' => $lat,
        'longitude' => $long,
    );

    $db->update(BUSINESS_TABLE, $geocode_fields, 'id', $id);
}

function add_cat_relationships($id, $categories)
{
    global $db;

    foreach ($categories AS $cat)
    {
        $fields = array(
            'business_id' => $id,
            'cat_id' => $cat,
        );
        $db->insert(BUSINESS_CAT_TABLE, $fields);
    }
}

function delete_cat_relationships($business_id)
{
    global $db;
    $db->delete(BUSINESS_CAT_TABLE, 'business_id', $business_id);
}

function get_category_post_fields()
{
    $results = array();

    $results['name'] = (!empty($_POST['category'])) ? trim($_POST['category']) : '';
    if (!empty($_POST['parent_category']))
    {
        $results['parent_id'] = $_POST['parent_category'];
    }

    return $results;
}

function get_business_categories($business_id)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT c.id, c.name, c.parent_id
        FROM ".CAT_TABLE." c
        INNER JOIN ".BUSINESS_CAT_TABLE." bc ON bc.cat_id = c.id
        WHERE bc.business_id = :id
    ");

    $query->execute(array('id' => $business_id));

    $categories = array();
    while ($result = $query->fetchObject())
    {
        $categories[] = $result;
    }

    return $categories;
}

function get_category_businesses($category_id)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".BUSINESS_TABLE."
        WHERE id IN
            (SELECT business_id
            FROM ".BUSINESS_CAT_TABLE."
            WHERE cat_id = :catid)
        ORDER BY name ASC
    ");

    $query->execute(array('catid' => $category_id));

    $businesses = array();
    while ($result = $query->fetchObject())
    {
        $businesses[] = $result;
    }

    return $businesses;
}

function get_main_categories()
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".CAT_TABLE."
        WHERE parent_id IS NULL
        ORDER BY name
    ");
    $query->execute();

    $categories = array();
    while ($result = $query->fetchObject())
    {
        $categories[] = $result;
    }

    return $categories;
}

function get_children_categories($id)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".CAT_TABLE."
        WHERE parent_id = :id
        ORDER BY name
    ");
    $query->execute(array('id' => $id));

    $categories = array();
    while ($result = $query->fetchObject())
    {
        $categories[] = $result;
    }

    return $categories;
}

function get_category($id)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".CAT_TABLE."
        WHERE id = :id
        LIMIT 1
    ");

    $query->execute(array('id' => $id));

    return $query->fetchObject();
}

function add_category($fields)
{
    global $db;
    $success = $db->insert(CAT_TABLE, $fields);
    if ($success)
    {
        set_session_msg('success', '"'.$fields['name'].'" added.');
        return true;
    }
    else
    {
        set_session_msg('error', 'Error adding category: database error.');
        return false;
    }
}

function update_category($id, $fields)
{
    global $db;
    $success = $db->update(CAT_TABLE, $fields, 'id', $id);
    if ($success)
    {
        set_session_msg('success', '"'.$fields['name'].'" updated.');
        return true;
    }
    else
    {
        set_session_msg('error', 'Error updating category: database error.');
        return false;
    }
}

function delete_category($id)
{
    global $db;
    $db->delete(CAT_TABLE, 'id', $id);
}

function get_user_post_fields( $mode='add' )
{
    if ($mode == 'add')
    {
        $results['user_name'] = (!empty($_POST['new_user_name'])) ? trim($_POST['new_user_name']) : '';
        $results['user_email'] = (!empty($_POST['new_user_email'])) ? trim($_POST['new_user_email']) : '';
        $results['user_pw'] = (!empty($_POST['new_user_password'])) ? trim($_POST['new_user_password']) : '';
        $results['user_pw_repeat'] = (!empty($_POST['new_user_password_repeat'])) ? trim($_POST['new_user_password_repeat']) : '';
        $results['user_type'] = (!empty($_POST['new_user_admin'])) ? '1' : '0';
    }
    else
    {
        $results['user_name'] = (!empty($_POST['update_user_name'])) ? trim($_POST['update_user_name']) : '';
        $results['user_email'] = (!empty($_POST['update_user_email'])) ? trim($_POST['update_user_email']) : '';
        $results['user_pw'] = (!empty($_POST['update_user_password'])) ? trim($_POST['update_user_password']) : '';
        $results['user_pw_repeat'] = (!empty($_POST['update_user_password_repeat'])) ? trim($_POST['update_user_password_repeat']) : '';
        $results['user_type'] = (!empty($_POST['update_user_admin'])) ? '1' : '0';
    }

    return $results;
}

function get_users()
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".USER_TABLE."
        ORDER BY user_name
    ");

    $query->execute();

    $users = array();
    while ($result = $query->fetchObject())
    {
        $users[] = $result;
    }

    return $users;
}

function get_user($arg)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".USER_TABLE."
        WHERE user_id = :arg
        OR user_name = :arg
        OR user_email = :arg
        LIMIT 1
    ");

    $query->execute(array('arg' => $arg));

    return $query->fetchObject();
}

function check_for_duplicate_user($user_name, $user_email)
{
    global $db;

    $query = $db->dbo->prepare("
        SELECT *
        FROM ".USER_TABLE."
        WHERE user_name = :user_name
        OR user_email = :user_email
        LIMIT 1
    ");

    $query->execute(array('user_name' => $user_name, 'user_email' => $user_email));

    return $query->fetchObject();
}

function add_user($fields)
{
    global $db;

    $duplicate = check_for_duplicate_user($fields['user_name'], $fields['user_email']);

    if ($duplicate)
    {
        set_session_msg('error', 'Adding failed: username or email already exists.');
        return false;
    }
    else
    {
        if($fields['user_pw'] != $fields['user_pw_repeat'])
        {
            set_session_msg('error', 'Adding failed: passwords do not match.');
            return false;
        }
        else
        {
            $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
            $user_password_hash = password_hash($fields['user_pw'], PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
            $fields['user_password_hash'] = $user_password_hash;
            $fields['user_active'] = '1';
            unset($fields['user_pw']);
            unset($fields['user_pw_repeat']);
            $success = $db->insert(USER_TABLE, $fields);
            if ($success)
            {
                set_session_msg('success', 'User "'.$fields['user_name'].'" added.');
                return true;
            }
            else
            {
                set_session_msg('error', 'Error adding user: database error.');
                return false;
            }
        }
    }
}

function update_user($id, $fields)
{
    global $db;

    if(!empty($_POST['password_change']))
    {
        if($fields['user_pw'] != $fields['user_pw_repeat'])
        {
            set_session_msg('error', 'Adding failed: passwords do not match.');
            return false;
        }
        else
        {
            $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
            $user_password_hash = password_hash($fields['user_pw'], PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
            $fields['user_password_hash'] = $user_password_hash;
        }
    }
    unset($fields['user_pw']);
    unset($fields['user_pw_repeat']);

    $success = $db->update(USER_TABLE, $fields, 'user_id', $id);
    if ($success)
    {
        set_session_msg('success', 'User "'.$fields['user_name'].'" updated.');
        return true;
    }
    else
    {
        set_session_msg('error', 'Error updating user: database error.');
        return false;
    }
}

function delete_user($id)
{
    global $db;
    $db->delete(USER_TABLE, 'user_id', $id);
}

function set_session_msg($type, $message)
{
    $_SESSION['feedback']['type'] = $type;
    $_SESSION['feedback']['message'] = $message;
}