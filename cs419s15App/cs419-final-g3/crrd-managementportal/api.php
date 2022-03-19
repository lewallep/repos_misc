<?php
/*
 * Main categories: 						api.php?type=category&parentid=none
 * Subcategories of particular category: 	api.php?type=category&parentid=[id]
 * Businesses in particular category:		api.php?type=business&catid=[id]
 * Business:								api.php?type=business&id=[id]
 */
	require_once('inc/functions.php');

	$result_data = array();

	if (!empty($_GET['type']))
	{
		if ($_GET['type'] == 'category')
		{
			if (!empty($_GET['parentid']))
			{
				$parent_id = $_GET['parentid'];
				if ($parent_id === 'none')
				{
					$result_data = get_main_categories();
				}
				else
				{
					$result_data = get_children_categories($parent_id);
				}
			}
			else
			{
				// Param "parentid" is missing.
			}
		}
		elseif ($_GET['type'] == 'business')
		{
			if ( (empty($_GET['id'])) && (!empty($_GET['catid'])) )
			{
				$result_data = get_category_businesses($_GET['catid']);
			}
			elseif ( (!empty($_GET['id'])) && (empty($_GET['catid'])) )
			{
				$result_data = get_business_details($_GET['id']);
			}
		}
		else
		{
			// Param "type" is not valid.
		}
	}
	else
	{
		// Param "type" is missing.
	}

	echo json_encode($result_data);