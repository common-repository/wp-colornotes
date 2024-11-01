<?php

function wp_colornotes_edit_page() {
	global $wpdb;
	global $wp_colornotes_table_name;
  
	// *** ColorNotes Info
	$id_notes      	 = $_REQUEST["wp_colornotes_id_notes"];
	$notes_title		 = "";
	$notes_message 	 = "";
	$notes_date    	 = date("Y-m-d");
	$notes_date_expires = date("Y-m-d",time(date("Y-m-d")) + (3600 * 24 * 30));
			
	// *******  SAVE NOTE ********
	if ( !($_POST["wp_colornotes_submit"] == "ok") )
	{
		if ($id_notes)
		{   $query = "SELECT * FROM " . $wpdb->prefix . $wp_colornotes_table_name .  
							 			     " WHERE id_notes = $id_notes ";
			
		    $notesInfo = $wpdb->get_results($query);
			
			$notes_title		 = $notesInfo[0]->notes_title;
			$notes_message 	 = $notesInfo[0]->notes_message;
			$notes_date    	 = $notesInfo[0]->notes_date;
			$notes_date_expires = $notesInfo[0]->notes_date_expires;
		}
	}
	else
	{
		   // *** notess Data
		   $notes_title        = str_replace("\'"," ",$_POST["wp_colornotes_notes_title"]);
		   $notes_message 	    = str_replace("\'"," ",$_POST["wp_colornotes_notes_message"]);
		   $notes_date    	    = str_replace("\'"," ",$_POST["wp_colornotes_notes_date"]);
		   $notes_date_expires = str_replace("\'"," ",$_POST["wp_colornotes_notes_date_expires"]);
		   
		   if ($id_notes)
		   {
			    	 $query = " UPDATE " . $wpdb->prefix . $wp_colornotes_table_name .  
				   				  " SET notes_title   		= '$notes_title', " . 
				   				      " notes_message 		= '$notes_message', " . 
				   				      " notes_date  		= '$notes_date',  "  . 
				   				      " notes_date_expires = '$notes_date_expires' " . 
			    	 		 " WHERE id_notes = $id_notes ";
			    	 
			    	$wpdb->query($query);
		   }
		   else
		   {
				    $query = "INSERT INTO " . $wpdb->prefix . $wp_colornotes_table_name  .  
				   					 "( notes_title, notes_message, notes_date, notes_date_expires )  " . 
				   				"VALUES ('$notes_title', '$notes_message', '$notes_date', '$notes_date_expires') "; 
			    
			     
			   		 $wpdb->query($query);
			    	 $lastID = $wpdb->get_results("SELECT MAX(id_notes) as lastid_notes " .
			    		  						   " FROM " . $wpdb->prefix . $wp_colornotes_table_name .
			    							 	  " WHERE notes_title = '$notes_title'");
			    	 
			    	 $id_notes = $lastID[0]->lastid_notes;
		  }
			    
	}
	 

?>
<div class="wrap">
<script type="text/javascript">
	function validateInfo(forma)
	{
		if (forma.wp_colornotes_notes_title.value == "")
		{
			alert("You must type a title");
			forma.wp_colornotes_notes_title.focus();
			return false;
		}
		
		if (forma.wp_colornotes_notes_message.value == "")
		{
			alert("You must type a notes message");
			forma.wp_colornotes_notes_message.focus();
			return false;
		}
		
		if (forma.wp_colornotes_notes_date.value == "")
		{
			alert("The date cannot be empty");
			forma.wp_colornotes_notes_date.focus();
			return false;
		}
		
		if (forma.wp_colornotes_notes_date_expires.value == "")
		{
			alert("The expire date cannot be empty");
			forma.wp_colornotes_notes_date_expires.focus();
			return false;
		}
		
		
	return true;
}
</script>

<form name="wp_colornotes_form" method="post" onsubmit="return validateInfo(this);" 
	  action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	  

<?php
    // Now display the options editing screen

    // header
	if ($id_notes)
    	echo "<h2>" . __( 'Edit Note',    'mt_trans_domain' ) . "</h2>";
    else
       	echo "<h2>" . __( 'Add New Note', 'mt_trans_domain' ) . "</h2>";

    // options form
    
 ?>
    <?php if ( $_POST["wp_colornotes_submit"] == "ok" ) { ?>
    <div class="updated"><p><strong><?php _e('Note information saved.', 'mt_trans_domain' ); ?></strong></p></div><br>	
    <? }; ?>

 	
 	<span class="stuffbox" >
 		
		 <label for="wp_colornotes_notes_date">Date</label>
		 <span class="inside">	
		 	<input type="text" size="10" maxlength="11" id="wp_colornotes_notes_date" name="wp_colornotes_notes_date"
		 		   value="<?php echo $notes_date ?>"> 
	     </span>
	     
	     <br>
	     
	     <label for="wp_colornotes_notes_date_expires">Expires</label>
		 <span class="inside">	
		 	<input type="text" size="10" maxlength="11" id="wp_colornotes_notes_date_expires" name="wp_colornotes_notes_date_expires"
		 		   value="<?php echo $notes_date_expires ?>"> 
	     </span>
	     
	     <br>
	     
	     <label for="wp_colornotes_notes_title">Title</label>
		 <span class="inside">	
		 	<input type="text" size="15" maxlength="15" id="wp_colornotes_notes_title" name="wp_colornotes_notes_title"
		 		   value="<?php echo $notes_title ?>"> 
	     </span>
	     
	     <br>
	     
	     <label for="wp_colornotes_notes_message">Message</label>
		 <span class="inside">	
		 	<input type="text" size="20" maxlength="100" id="wp_colornotes_notes_message" name="wp_colornotes_notes_message"
		 		   value="<?php echo $notes_message ?>"> 
	     </span>
	     
	     <br>
	     
 	</span>
 

<p class="submit">
	<input type="hidden" name="wp_colornotes_submit" value="ok">
	<input type="hidden" name="wp_colornotes_id_notes" value="<?php echo $id_notes ?>">
	<input type="submit" name="Submit" value="<?php _e('Save Note Information', 'mt_trans_domain' ) ?>" />&nbsp;
	<input type="button" name="Return" value="<?php _e('Return to Notes List', 'mt_trans_domain' ) ?>"
		   onclick="document.location='options-general.php?page=wp_colornotes' " />
</p>

</form>

</div> <!-- **** DIV WRAPPER *** -->

<?php } ?>