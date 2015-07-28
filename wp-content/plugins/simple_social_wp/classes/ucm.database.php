<?php

function ucm_query($sql){
	global $wpdb;
	return $wpdb->query($sql);
}
function ucm_qa1($sql){
	global $wpdb;
	return $wpdb->get_row($sql, ARRAY_A);
}
function ucm_get_single($table, $primary_key_name, $primary_key_value){
	global $wpdb;
	$primary_key_name = is_array($primary_key_name) ? $primary_key_name : array($primary_key_name);
	$primary_key_value = is_array($primary_key_value) ? $primary_key_value : array($primary_key_value);
	$sql = "SELECT * FROM `"._SIMPLE_SOCIAL_DB_PREFIX.esc_sql($table)."` WHERE 1 ";
	foreach($primary_key_name as $id => $name){
		$sql .= " AND `".esc_sql($name)."` = '".esc_sql($primary_key_value[$id])."'";
	}
	return $wpdb->get_row($sql, ARRAY_A);
}

function ucm_update_insert($primary_key_name, $primary_key_value, $table_name, $data){
	global $wpdb;
	if(!is_array($data))$data = array();
	// does it exist?
	$exists = $primary_key_value ? ucm_get_single($table_name, $primary_key_name, $primary_key_value) : false;
	if(!$exists){
		$data[$primary_key_name] = $primary_key_value;
		$wpdb->replace(_SIMPLE_SOCIAL_DB_PREFIX.$table_name, $data);
		return $wpdb->insert_id;
	}else if($primary_key_value){
		$stat = $wpdb->update(_SIMPLE_SOCIAL_DB_PREFIX.$table_name, $data, array($primary_key_name => $primary_key_value));
		return $primary_key_value;
	}else{
		echo 'database update error - please report this';
		exit;
	}
}
function ucm_get_multiple($table, $search, $index){
	global $wpdb;
	$sql = "SELECT * FROM `"._SIMPLE_SOCIAL_DB_PREFIX.esc_sql($table)."` WHERE 1 ";
	foreach($search as $key=>$val){
		$sql .= " AND `".esc_sql($key)."` = '".esc_sql($val)."'";
	}
	$res = $wpdb->get_results($sql, ARRAY_A);
	$return = array();
	foreach($res as $r){
		if($index && isset($r[$index])){
			$return[$r[$index]] = $r;
		}else{
			$return = $res;
			break;
		}
	}
	return $return;
}
function ucm_delete_from_db($table, $key, $val){
	global $wpdb;
	$wpdb->delete(_SIMPLE_SOCIAL_DB_PREFIX.$table, array($key=>$val));
}

function ucm_print_date($time, $include_time=false){
	return date('Y-m-d' .  ($include_time ? ' H:i:s' : '') , $time);
}

function ucm_process_pagination($rows,$per_page = 20,$page_number = 0,$table_id='table'){
	$data = array();
	$data['rows']=array();
	$data['links']='';
	if($per_page !== false && $per_page<=0){
		$per_page = 20;
	}

	$db_resource = false;
	if(is_resource($rows)){
		// have the db handle for the sql query
		$db_resource = $rows;
		unset($rows);
        $total = mysql_num_rows($db_resource);

	}else if(is_array($rows)){
		// we have the rows in an array.
		$total = count($rows);
	}else{
		echo 'Pagination failed. Please report this bug.';
		exit;
	}





    // pagination hooks
    ob_start();

    $pagination_hooks = ob_get_clean();

	// default summary/links content
	ob_start();
	echo '<div class="pagination_summary"><p>';
	if($total > 0){
		_e('Showing records %s to %s of %s',(($page_number*$per_page)+1),$total,$total);
        echo $pagination_hooks;
	}else{
		_e('No results found');
	}
	echo '</p></div>';
	$data['summary'] = ob_get_clean();
	ob_start();
	echo '<div class="pagination_links">';
	//echo "\n<p>";
	echo sprintf(__('Page %s of %s','simple_social_inbox'),1,1);
	//echo '</p>';
	echo '</div>';
	$data['links']=ob_get_clean();
    $data['page_numbers'] = 1;
	if($per_page === false || $total<=$per_page){

        if($db_resource){
            $rows = array();
            //if($per_page !== false && $total<=$per_page){
            // pull out all records.
            while($row = mysql_fetch_assoc($db_resource)){
                $rows[] = $row;
            }
            if(mysql_num_rows($db_resource)>0){
                mysql_data_seek($db_resource,0);
            }
            //}
        }

		$data['rows']=$rows;
	}else{

        if(isset($_REQUEST['pg'.$table_id])){
            $page_number = $_REQUEST['pg'.$table_id];
        }
        if($table_id && $table_id !='table' && $total>$per_page){
            // we remember the last page number we were on.
            if(!isset($_SESSION['_table_page_num'])){
                $_SESSION['_table_page_num'] = array();
            }
            if(!isset($_SESSION['_table_page_num'][$table_id])){
                $_SESSION['_table_page_num'][$table_id] = array(
                    'total_count' => 0,
                    'page_number' => 0,
                );
            }
            $_SESSION['_table_page_num'][$table_id]['total_count'] = $total;

            if(isset($_REQUEST['pg'.$table_id])){
                $page_number = $_REQUEST['pg'.$table_id];
            }else if($_SESSION['_table_page_num'][$table_id]['total_count']==$total){
                $page_number = $_SESSION['_table_page_num'][$table_id]['page_number'];
            }
            $_SESSION['_table_page_num'][$table_id]['page_number'] = $page_number;
            //echo $table_id.' '.$total . ' '.$per_page.' '.$page_number; print_r($_SESSION['_table_page_num']);
        }
        $page_number = min(ceil($total/$per_page)-1,$page_number);



		// slice up the result into the number of rows requested.
		if($db_resource){
			// do the the mysql way:
			mysql_data_seek($db_resource, ($page_number*$per_page));
			$x=0;
			while($x < $per_page){
				$row_data = mysql_fetch_assoc($db_resource);
				if($row_data){
					$data['rows'] [] = $row_data;
				}
				$x++;
			}
			unset($row_data);
		}else{
			// the old array way.
			$data['rows']=array_slice($rows, ($page_number*$per_page), $per_page);
		}
		$data['summary']='';
		$data['links']='';
		$request_uri = preg_replace('/[&?]pg'.preg_quote($table_id).'=\d+/','',$_SERVER['REQUEST_URI']);
		$request_uri .= (preg_match('/\?/',$request_uri)) ? '&' : '?';
		$request_uri = htmlspecialchars($request_uri);
		if(count($data['rows'])){

			$page_count = ceil($total/$per_page);
			// group into ranges with cute little .... around the numbers if there's too many.
			$rangestart = max(0,$page_number-5);
			$rangeend = min($page_count-1,$page_number+5);

			ob_start();
			echo '<div class="pagination_summary">';
			echo '<p>';
            _e('Showing records %s to %s of %s',(($page_number*$per_page)+1),(($page_number*$per_page)+count($data['rows'])),$total);
            //echo 'Showing records ' . (($page_number*$per_page)+1) . ' to ' . (($page_number*$per_page)+count($data['rows'])) .' of ' . $total . '</p>';
            echo $pagination_hooks;
            echo '</p>';
			echo '</div>';
			$data['summary'] = ob_get_clean();
			ob_start();
			echo '<div class="pagination_links">';
			//echo "\n<p>";
            echo '<span>';
			if($page_number > 0){ ?>
			    <a href="<?php echo $request_uri;?>pg<?php echo $table_id;?>=<?php echo $page_number-1;?>#t_<?php echo $table_id;?>" rel="<?php echo $page_number-1;?>"><?php _e('&laquo; Prev');?></a> |
			<?php  } else{ ?>
			    <?php _e('&laquo; Prev');?> |
			<?php  }
            echo '</span>';
            if($rangestart>0){
				?> <span><a href="<?=$request_uri;?>pg<?php echo $table_id;?>=0#t_<?php echo $table_id;?>" rel="0" class="">1</a></span> <?php
				if($rangestart>1)echo ' ... ';
			}
			for($x=$rangestart;$x<=$rangeend;$x++){
				if($x == $page_number){
					?>
					<span><a href="<?=$request_uri;?>pg<?php echo $table_id;?>=<?=$x;?>#t_<?php echo $table_id;?>" rel="<?=$x;?>" class="current"><?=($x+1);?></a></span>
					<?php
				}else{
					?>
					<span><a href="<?=$request_uri;?>pg<?php echo $table_id;?>=<?=$x;?>#t_<?php echo $table_id;?>" rel="<?=$x;?>" class=""><?=($x+1);?></a></span>
					<?php
				}
			}
			if($rangeend < ($page_count-1)){
				if($rangeend < ($page_count-2))echo ' ... ';
				?> <span><a href="<?=$request_uri;?>pg<?php echo $table_id;?>=<?=($page_count-1);?>#t_<?php echo $table_id;?>" rel="<?=($page_count-1);?>" class=""><?=($page_count);?></a></span> <?php
			}

			if($page_number < ($page_count-1)){ ?>
			    | <span><a href="<?php echo $request_uri;?>pg<?php echo $table_id;?>=<?php echo $page_number+1;?>#t_<?php echo $table_id;?>" rel="<?php echo $page_number+1;?>"><?php _e('Next &raquo;');?></a><span>
			<?php  } else{ ?>
			    | </span><?php _e('Next &raquo;');?><span>
			<?php  }
            //echo '</p>';
			echo '</div>';
			?>
			<script type="text/javascript">
				$(function(){
					$('.pagination_links a').each(function(){
						// make the links post the search bar on pagination.
						$(this).click(function(){
							// see if there's a search bar to post.
							var search_form = false;
							search_form = $('.search_form')[0]
							$('.search_bar').each(function(){
								var form = $(this).parents('form');
								if(typeof form != 'undefined'){
									search_form = form;
								}
							});
							if(typeof search_form == 'object'){
								$(search_form).append('<input type="hidden" name="pg<?php echo $table_id;?>" value="'+$(this).attr('rel')+'">');
								search_form = search_form[0];
								if(typeof search_form.submit == 'function'){
									search_form.submit();
								}else{
									$('[name=submit]',search_form).click();
								}
								return false;
							}
						});
					});
				});
			</script>
			<?php
			$data['links']=ob_get_clean();

            $data['page_numbers'] = $page_count;
		}
	}
	return $data;
}


function ucm_forum_text($original_text){

	return wpautop(make_clickable($original_text));

}
