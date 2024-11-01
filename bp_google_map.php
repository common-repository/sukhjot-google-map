<?php 
	/*
		Plugin name:Google Map With Short Code
		Description:This plugin will show google map in your post or pages with the help of short code then will plugin will place a link and your google map will be view in a lightbox .
		Author:Sukhchain Singh
		Author URI:http://anyquestion.webvertex.co.in
		Version:1.3
	*/
	
register_activation_hook(__FILE__,'install_plugin_fun');
function install_plugin_fun()
{
	add_option('gmap_key','');
	add_option('gmap_link_txt','GMAP');
	add_option('gmap_width','500');
	add_option('gmap_height','500');
	add_option('gmap_type_view','G_NORMAL_MAP');
	add_option('gmap_zoom','13');
	
	
}	
add_action('admin_menu','main_menu_fun');
function main_menu_fun()
{
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page('gmap', 'GMAP','manage_options','GMAP','gmap_fun', '');
}	
function gmap_fun()
{
 if(!empty($_POST))
 {
	if(empty($_POST['gmap_link_txt']) || empty($_POST['gmap_key']) || empty($_POST['gmap_width']) || empty($_POST['gmap_height']) || empty($_POST['gmap_type_view']) || empty($_POST['gmap_zoom']))
	{
		echo '<script type="text/javascript" >window.location="?page=GMAP&msg=2"</script>';
	}
	else
	{
		update_option('gmap_key',$_POST['gmap_key']);
		update_option('gmap_link_txt',$_POST['gmap_link_txt']);
		update_option('gmap_width',$_POST['gmap_width']);
		update_option('gmap_height',$_POST['gmap_height']);
		update_option('gmap_type_view',$_POST['gmap_type_view']);
		update_option('gmap_zoom',$_POST['gmap_zoom']);
		
		echo '<script type="text/javascript" >window.location="?page=GMAP&msg=1"</script>';
	}	
	
 }
?>
	<h2>GMAP</h2>
	<?php
		if(isset($_GET) && $_GET['msg']==1)
		{ ?>
			<div class="updated" id="message">
				<p><strong>Changes saved !</strong></p>
			</div>
	<?php	}
			elseif(isset($_GET) && $_GET['msg']==2){ ?>
			<div class="updated" id="message">
				<p><strong>Please fill fields marked with (*) !</strong></p>
			</div>
			<?php 
			} ?>
<?php 
	$class="width:150px;display:inline-block;";
?>
	<form method="post" id="gmap_form">
	<ul>
		<li>
			<label style="<?php echo $class?>">Google Map API KEY</label>
			*<input type="text" name="gmap_key" size="50" value="<?php echo get_option('gmap_key');?>"/>
		</li>
		<li>
			<label style="<?php echo $class?>">GMAP Link Text</label>
			*<input type="text" name="gmap_link_txt" size="50" value="<?php echo get_option('gmap_link_txt');?>" />
		</li>
		<li>
			<label style="<?php echo $class?>">Google Map Width</label>
			*<input type="text" name="gmap_width" size="50" value="<?php echo get_option('gmap_width');?>"/>
		</li>
		<li>
			<label style="<?php echo $class?>">Google Map Height</label>
			*<input type="text" name="gmap_height" size="50" value="<?php echo get_option('gmap_height');?>"/>
		</li>
		<li>
			<?php 
				$MTypes=array(
						'G_NORMAL_MAP'	 		=>'NORMAL MAP',
						'G_SATELLITE_MAP' 		=>'SATELLITE MAP',
						'G_HYBRID_MAP'			=>'HYBRID MAP',
						'G_DEFAULT_MAP_TYPES'	=>'DEFAULT MAP TYPES',
						'G_PHYSICAL_MAP'		=>'PHYSICAL MAP'
				);
			?>
			<label style="<?php echo $class?>">Google Map View Type</label>
			*<select name="gmap_type_view">
				<option value="">-----------------------------</option>
				<?php foreach($MTypes as $V=>$K):?>
					
					<option value="<?php echo $V?>" <?php echo ($V==get_option('gmap_type_view')) ? 'selected="selected"'  : ''?>><?php echo $K?></option>
				<?php endforeach?>
				
			</select>
		</li>
		<!--	G_NORMAL_MAP displays the default road map view.
				G_SATELLITE_MAP displays Google Earth satellite images. *
				G_HYBRID_MAP displays a mixture of normal and satellite views.*
				G_DEFAULT_MAP_TYPES contains an array of the above three types, useful for iterative 	processing.
				G_PHYSICAL_MAP displays a physical map based on terrain information.-->
		<li>
			<label style="<?php echo $class?>">Google Map Zoom</label>
				*<select name="gmap_zoom">
					<?php for($zm=1;$zm<20;$zm++):?>
						<option value="<?php echo $zm?>" <?php echo ($zm==get_option('gmap_zoom')) ? 'selected="selected"' : ''?>><?php echo $zm?></option>
					<?php endfor;?>					
				</select>
		</li>
		<li>
			<input type="submit" value="Save" />
		</li>
	</ul>
	</form>
<?php }
add_action('wp_head', 'add_my_js_css', 1);  	
function add_my_js_css()
{

	?><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__)?>fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__)?>fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__)?>fancybox/jquery.fancybox-1.3.4.css" media="screen" />


<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo get_option('gmap_key');?>"
            type="text/javascript"></script>
			<script type="text/javascript"> 
    var map = null;
    var geocoder = null;
	//var address="ludhiana punjab india";
    function initialize(address,id,desc) {

      if (GBrowserIsCompatible()) {
        //map = new GMap2(document.getElementById("map_canvas"+id));
        //map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        geocoder = new GClientGeocoder();
      } 
	  showAddress(address,id,desc);
    }
 
    function showAddress(address,id,desc) {
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
				
			  map = new GMap2(document.getElementById("map_canvas"+id));
              map.setCenter(point,<?php echo get_option('gmap_zoom');?>);
			  /*
				G_NORMAL_MAP displays the default road map view.
				G_SATELLITE_MAP displays Google Earth satellite images. *
				G_HYBRID_MAP displays a mixture of normal and satellite views.*
				G_DEFAULT_MAP_TYPES contains an array of the above three types, useful for iterative 	processing.
				G_PHYSICAL_MAP displays a physical map based on terrain information.
			  */
			  
			 map.setMapType(<?php echo get_option('gmap_type_view');?>);
              var marker = new GMarker(point);
             
 
              // As this is user-generated content, we display it as
              // text rather than HTML to reduce XSS vulnerabilities.
			  //var cnt="The Corn Exchange, Fenwick Street,<br />Liverpool, L2 7QL"; 
					
			  GEvent.addListener(marker, "mouseover", function() {
				marker.openInfoWindowHtml('<b>'+desc+'</b>');
				openInfoWindowHtml.close();
			});
			
			
               map.addOverlay(marker);
			   map.setUIToDefault();
			  
            }
          }
        );
      }
    }

    </script> 
<script type="text/javascript">
		$(document).ready(function() {
			
			$(".various1").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});

			
		});
	</script>
<?php }

function add_map_link($atts)
{
	extract( shortcode_atts( array(
							  'suburb' => '',
							  'state' => '',
							  'country' => '',
							  'description' =>'',
							  ), $atts ) );
	$address1		= $atts['suburb']." ".$atts['state']." ".$atts['country'];
	$address		= ucwords($address1);
	$description		= ucwords($atts['description']);
	?>		
	<span>
		<a class="various1" href="#inline<?php echo time() ?>" onclick="initialize('<?php echo $address?>',<?php echo time() ?>,'<?php echo $description?>');"  title="map">
			<?php echo get_option('gmap_link_txt');?>
		</a>
	</span>
	<div style="display: none;">
		<div id="inline<?php echo time() ?>" style="width:<?php echo get_option('gmap_width');?>px;height:<?php echo get_option('gmap_height');?>px;overflow:auto;">
			<div id="map_canvas<?php echo time() ?>" style="width:<?php echo get_option('gmap_width');?>px; height:<?php echo get_option('gmap_height');?>px;"></div>
		</div>
	</div>
	<?php
}

add_shortcode('map_link','add_map_link');
?>
