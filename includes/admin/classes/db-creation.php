<?php

class MXZSM_Database_Creation
{

	public static function cerate_tbs() {
				
		global $wpdb;

		// Create table regions
		// Table name
		$table_name = $wpdb->prefix . 'regions';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'" ) !=  $table_name ) {

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name`
			(
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`region` varchar(40) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=$wpdb->charset AUTO_INCREMENT=1;";

			$wpdb->query( $sql );

		}

		// Create table cities
		// Table name
		$table_name = $wpdb->prefix . 'cities';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'" ) !=  $table_name ) {

			$sql = "CREATE TABLE IF NOT EXISTS `$table_name`
			(
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`city` varchar(40) NOT NULL,
				`region_id` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=$wpdb->charset AUTO_INCREMENT=1;";

			$wpdb->query( $sql );

		}

	}

	public static function insert_data() {

		global $wpdb;

		$table_name_region = $wpdb->prefix . 'regions';

		$table_name_cities = $wpdb->prefix . 'cities';

		$file_path = MXZSM_PLUGIN_ABS_PATH . 'assets/reigions-cities.csv';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name_region . "'" ) !=  $table_name_region )
				return;

		// check if the data exists
		$regions_conut = $wpdb->get_var( "SELECT COUNT( id ) FROM $table_name_region" );

		if( $regions_conut == 0) :

			if ( ( $handle = fopen( $file_path, "r" ) ) !== FALSE ) {

				// 1. create region data
				$regions_array = array();

				while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {

					$num = 2;

			        for ( $c = 0 ; $c < $num; $c++ ) {        	

				    	if( !in_array( $data[1], $regions_array ) ) {

				    		array_push( $regions_array, $data[1] );

				    	}

					}

				}

				// insert data
			    foreach ( $regions_array as $key => $value ) {

					// insert regions
					$wpdb->insert(
						$table_name_region,
						array( 'region' => $value ),
						array( '%s' )
					);

			    }

			    fclose( $handle );

			}

		endif;

		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name_cities . "'" ) !=  $table_name_cities )
				return;

		$cities_conut = $wpdb->get_var( "SELECT COUNT( id ) FROM $table_name_cities" );

		if( $cities_conut == 0) :
		    // 2. set cities
		    if ( ( $handle = fopen( $file_path, "r" ) ) !== FALSE ) {

		    	while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {

			        $num = 2;

			        for ( $c = 0 ; $c < $num; $c++ ) { 

			        	if( $c == 0 ) {

			        		// get data from regions table
			        		$region = $wpdb->get_row( "SELECT id, region FROM $table_name_region WHERE region = '$data[1]'" );

			        		// insert cities
			        		$wpdb->insert(
								$table_name_cities,
								array(
									'city' 			=> $data[0],
									'region_id'		=> $region->id
								),
								array( '%s', '%d' )
							);

			        	} 

			        }

			    }

			    fclose( $handle );

			}

		endif;


	}

}