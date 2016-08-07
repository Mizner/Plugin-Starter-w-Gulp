<?php
//function some_function( $some_parameter ) {
//	global $wpdb;
//	$table   = $wpdb->prefix . "zip_codes";
//	$field   = "zip_matches";
//	$results = $wpdb->get_results(
//		$wpdb->prepare( "SELECT count(ID) as total FROM $table WHERE $field", $some_parameter )
//	);
//}

function zip_codes() {
	global $wpdb;
	$service_areas_table = $wpdb->prefix . 'zip_codes';
	$the_query           = "
	SELECT * 
	FROM $service_areas_table
	";
	$zipCodes            = $wpdb->get_results( $the_query );

	return $zipCodes;
}

class ServiceAreaPage {
	// property declaration
	public $title = "Services Areas";

	// method declaration
	public function displayVar() {
		echo "<div class='wrap'>" . "<h1>{$this->title}</h1>";
		//ob_start();
		$zip_codes = zip_codes();
		?>
		<h2>Add New</h2>
		<label for="zip_radius"><strong>Mile Radius:</strong></label>
		<input id="zipRadius" name="zip_radius" class="small-text">
		<label for="zip_code"><strong>Zip Code:</strong></label>
		<input id="zipCode" maxlength="5" name="zip_code" class="zipcode" type="text" value="" pattern="\d*">
		<button id="addServiceAreaButton" class="button-primary">Add Service Area</button>
		<br class="clear">
		<h2>Existing Areas</h2>
		<table class="widefat">
			<thead>
			<tr>
				<th></th>
				<th class="row-title">Zip code</th>
				<th>Radius</th>
				<th>Zip Code Matches</th>
			</tr>
			</thead>
			<tbody id="serviceAreas">
			<?php
			$rowCount = 0;

			foreach ( $zip_codes as $item => $value ) {
				if ( $rowCount ++ % 2 == 1 ) {
					echo "<tr id='{$value->zip_code}' class='alternate'>";
				} else {
					echo "<tr id='{$value->zip_code}'>";
				}
				echo
					"<td class='remove-service_area'><button class='dashicons dashicons-no-alt button removeButton' value='{$value->zip_code}'></button></td>" .
					"<td class='row-title'><label for='tablecell'>{$value->zip_code}</label></td>" .
					"<td>{$value->zip_radius}</td>" .
					"<td style='word-break: break-word;'><p>{$value->zip_matches}</p></td>" .
					"</tr>";
			}





			?>
			</tbody>
		</table>
		<br class="clear"/>
		<?php
		echo "</div>";
	}
}

class ServiceAreas {

	private $service_area_screen_name;
	private static $instance;

	static function GetInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function DashboardMenu() {
		$page_title                     = "Service Areas";
		$menu_title                     = "Service Areas";
		$capability                     = "manage_options";
		$menu_slug                      = "service_area";
		$function                       = array( $this, 'RenderPage' );
		$icon_url                       = "dashicons-location-alt";
		$position                       = "2";
		$this->service_area_screen_name = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}

	public function RenderPage() {
		$simple_class = new ServiceAreaPage();
		$simple_class->displayVar();
	}

	public function InitPlugin() {
		add_action( 'admin_menu', [ $this, 'DashboardMenu' ] );
	}
}

$ServiceArea = ServiceAreas::GetInstance();
$ServiceArea->InitPlugin();


