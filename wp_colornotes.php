<?php
/*
Plugin Name: WP ColorNotes
Plugin URI: http://www.lasvegas-allinclusive.com/wp-colornotes/
Description: This widget lets you put messages in colorful notes to capture visitors attention.
Author: MarkThom
Version: 1.0
Author URI: http://www.lasvegas-allinclusive.com
*/

// ******************************************
// *********** INSTALLATION ******************
// ******************************************

register_activation_hook(__FILE__,'wp_colornotes_install');

$wp_colornotes_table_name = "wp_colornotes_messages";

function wp_colornotes_install () {
   global $wpdb;
   $wp_colornotes_table_name = "wp_colornotes_messages";
   
   $table_name = $wpdb->prefix . $wp_colornotes_table_name;
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id_notes mediumint(9) NOT NULL AUTO_INCREMENT,
	  notes_date DATE NOT NULL,
	  notes_date_expires DATE NOT NULL,	  
	  notes_title VARCHAR(55) NOT NULL,
	  notes_message TEXT NOT NULL,
	  UNIQUE KEY id_notes (id_notes)
	);";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

   }
}



function wp_colornotes_getwidget()
{	
		$optStyle = get_option("wp_colornotes_style");
		if (!$optStyle)
		  $optStyle = "colornote_01.png";
		  
        return '<div id="wp_colornotes_widget" style="background:url(' . get_option("siteurl") 
    			    . '/wp-content/plugins/wp_colornotes/' . $optStyle .') no-repeat; ' .
    			    'width: 150px; height:120px;"><br>' .
  			 '<div id="wp_colornotes_title"   style="margin:0px 0px 0px 20px;"></div> ' .
    		 '<div id="wp_colornotes_message" style="margin:4px 0px 0px 20px; width:120px; height: 80px; border: 0px solid black;">' .
    		'</div> ' .
    	   '</div>' . 
    	    '<div>' ."\n" .   
				'<font style="font-size:6px;">Created by ' . 
					'<a href="http://www.lasvegas-allinclusive.com/" target="_TOP" title="Las Vegas All Inclusive">Las Vegas All Inclusive</a></font>' . 
             '</div>' . "\n";
	
}



function wp_colornotes_widget($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?><?php echo $after_title;
  echo wp_colornotes_getwidget();
  echo $after_widget;
}

function wp_colornotes_setmessages()
{  
	global $wpdb;
	global $wp_colornotes_table_name;
?>
	<script type="text/javascript">
		var arrMessages = new Array();
		var arrTitles   = new Array();
		
		<?php 
			
			$messages = $wpdb->get_results( 
											 "SELECT * FROM " . 
											 $wpdb->prefix .  $wp_colornotes_table_name .
											 " WHERE notes_date_expires > '" . date("Y-m-d") . "'" .
											 " ORDER BY notes_date"
										   );
		if ($messages)
		{										    
			for ($actualm = 0; $actualm < count($messages); $actualm++)
			{
		?>
		 	  arrMessages[<?php echo $actualm ?>] = "<?php echo $messages[$actualm]->notes_message ?>";
		 	  arrTitles[<?php echo $actualm ?>] = "<?php echo $messages[$actualm]->notes_title ?>";
		 	  
		 <? } ;?>
		
		var iActualMessages = 0;
		<?
			$optTimeInterval = get_option("wp_colornotes_timeinterval");

  			if (!$optTimeInterval || $optTimeInterval == "0")
				$optTimeInterval = "5";

			$optTimeInterval *= 1000;
			 
		?>
		setInterval ('wp_colornotes_setMessages();',<?php echo $optTimeInterval  ?>);
		
		function wp_colornotes_setMessages ()
		{
			var div = document.getElementById("wp_colornotes_message");
			if (div == null)
			  return;
			
			var divTitle = document.getElementById("wp_colornotes_title");
			  
			iActualMessages++;
			if (iActualMessages == arrMessages.length)
			 iActualMessages = 0;
			div.innerHTML      = arrMessages[iActualMessages];
			divTitle.innerHTML = "<b>" + arrTitles[iActualMessages] + "</b>";
		}
		
	</script>
<?php	
	};
}

function wp_colornotes_widget_control()
{
  
  $optTimeInterval = get_option("wp_colornotes_timeinterval");
  
  
  if (!$optTimeInterval)
		$optTimeInterval = "5";

  $style1checked = "";
  $style2checked = "";
  $optStyle = get_option("wp_colornotes_style");
  if ($optStyle == "colornotes_02.png")
  	$style2checked = "checked";
  else
  	$style1checked = "checked";
   
  
  
  if ($_POST['wp_colornotes-Submit'])
  {
	    update_option("wp_colornotes_timeinterval",  $_POST['wp_colornotes_timeinterval']);
	    update_option("wp_colornotes_style",  $_POST['wp_colornotes_style']);
  }
  
  ?>
   <p>
    <label for="wp_colornotes_timeinterval">Time interval between messages: </label>
    <input type="text" id="wp_colornotes_timeinterval" name="wp_colornotes_timeinterval" size="3" maxlength="3" 
    	   value="<?php echo $optTimeInterval;?>" /> seconds.<br>
    
    <table>
  <tr>
    <td colspan="6">ColorNote Style:</td>
  </tr>
  <tr>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_01.png" <?php echo $style1checked ?>></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb01.jpg' ?>"></td>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_02.png" <?php echo $style2checked ?>></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb02.jpg' ?>" alt="" /></td>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_03.png" <?php echo $style3checked ?> /></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb03.jpg' ?>" alt="" /></td>
    </tr>
  <tr>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_04.png" <?php echo $style4checked ?> /></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb04.jpg' ?>" alt="" /></td>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_05.png" <?php echo $style5checked ?> /></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb05.jpg' ?>" alt="" /></td>
    <td valign="middle"><input type="radio" name="wp_colornotes_style" value="colornotes_06.png" <?php echo $style6checked ?> /></td>
    <td><img src="<?php echo get_option("siteurl"). '/wp-content/plugins/wp_colornotes/colornotes_thumb06.jpg' ?>" alt="" /></td>
    </tr>
</table>
    <input type="hidden" id="wp_colornotes-Submit" name="wp_colornotes-Submit" value="1" />
  </p>
  <?php
};


include ("wp_colornotes_list.php");
include ("wp_colornotes_addedit.php");
// Hook for adding admin menus
add_action('admin_menu', 'wp_colornotes_add_pages');

// action function for above hook
function wp_colornotes_add_pages() {
    
	// Add a new submenu under Options:
	 if ($_REQUEST["wp_colornotes_id_postit"] || $_REQUEST["wp_colornotes_addnew"] )
   		add_options_page('WP ColorNotes', 'WP ColorNotes', 8, 'wp_colornotes', 'wp_colornotes_edit_page');
     else
		add_options_page('WP ColorNotes', 'WP ColorNotes', 8, 'wp_colornotes', 'wp_colornotes_list_page');
}





function wp_colornotes_init()
{
  register_sidebar_widget(__('WP ColorNotes'), 'wp_colornotes_widget');
  register_widget_control('WP ColorNotes', 'wp_colornotes_widget_control', 300, 200 );
}

add_action('wp_footer', 'wp_colornotes_setmessages');
add_action("plugins_loaded", "wp_colornotes_init");


?>
