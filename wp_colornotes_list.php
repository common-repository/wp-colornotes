<?php

function wp_colornotes_list_page()
	{
			
	  global $wpdb;
	  global $wp_colornotes_table_name;
	  
?>
<div class="wrap">

<script type="text/javascript">
	function delete_message(idmessage, title)
	{
		if (confirm("Are you sure you want to delete the message " +  title + "?"))
		{
			document.forms["wp_colornotes_listform"].wp_colornotes_messagetodelete.value = idmessage;
			document.forms["wp_colornotes_listform"].wp_colornotes_action.value = "delete";
			document.forms["wp_colornotes_listform"].submit();
		}
	}
</script>
<?php 
	if ( $_POST["wp_colornotes_action"] == "delete" )
	{
		$wpdb->query("DELETE FROM " . $wpdb->prefix .  $wp_colornotes_table_name .
					 " WHERE id_notes = " . $_POST["wp_colornotes_messagetodelete"] );	
	}
	
?>
<h2>WP ColorNotes</h2>
<form name="wp_colornotes_listform" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"
	  method="post" >
	<input type="hidden" name="wp_colornotes_messagetodelete" value="">
	<input type="hidden" name="wp_colornotes_action" value="">
				   
	<p class="submit">
		<input type="button" value="Add New Note" 
			   onclick="document.location='options-general.php?page=wp_colornotes&wp_colornotes_addnew=ok'">
		<br>
	</p>
</form>		
<br>
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col" class="manage-column column-name" style="">Note Message</th>
	<th scope="col" class="manage-column column-name" style="">Expires</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
</tr>
</thead>

<tbody id="users" class="list:user user-list">
<?php
	
	$query = " SELECT *  FROM " .
			  $wpdb->prefix . $wp_colornotes_table_name .
			  " ORDER BY notes_date, notes_title ";
			  
	$myMessages = $wpdb->get_results($query);
	
	foreach ($myMessages as $message)
	{
 ?>
        <tr id='user-1' class="alternate">
			<td class="username column-username">
				<img src="<?php echo get_option("siteurl") . 
    			        '/wp-content/plugins/wp_colornotes/colornotes-icon.jpg' ?>" border="0" align="middle">
				<?php echo $message->notes_title; ?>
		    </td>
		    <td class="username column-username">
				<?php echo $message->notes_date_expires; ?>
		    </td>
		   
		   <td class="username column-username">
				<a href="options-general.php?page=wp_colornotes&wp_colornotes_id_postit=<?php echo $message->id_notes; ?>">
				Edit Note</a>
		   </td>
		   <td class="username column-username">
				<a href="javascript:delete_message(<?php echo $message->id_notes ?>,'<?php echo $message->notes_title ?>');">
				Delete Note</a>
		   </td>		
		</tr>
        
        <?
    }

?>
	
    </tbody>
</table>
<?php 
	}

?>