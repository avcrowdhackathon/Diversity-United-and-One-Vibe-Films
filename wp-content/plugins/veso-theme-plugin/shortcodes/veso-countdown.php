<?php

class Veso_Countdown {
	function __construct() {
		add_shortcode( 'veso_countdown', array($this, 'veso_shortcode' ));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts'));

		if ( function_exists('vc_add_shortcode_param')){
			vc_add_shortcode_param('datetimepicker' , array($this, 'datetimepicker') ) ;
		}
		add_action( 'init', array($this, 'veso_map_vc_params' ));
	}
	function admin_scripts() {
		wp_enqueue_script('jquery-ui-datepicker');		
		wp_register_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  		wp_enqueue_style( 'jquery-ui-style' );  
	}

	function veso_shortcode( $atts ) {
		wp_enqueue_script('jquery.timecircle', get_template_directory_uri() .'/assets/js/libs/jquery.countdown_org.js','1.0',array('jQuery'));
		extract( shortcode_atts( array(
			'datetime' => '',
			'time' => '',
			'veso_tz'=>'veso-wptz',
			'countdown_opts'=>'syear,smonth,sweek,sday',
			'el_class'=>'',
			'string_days' => 'Day',
			'string_days2' => 'Days',
			'string_weeks' => 'Week',
			'string_weeks2' => 'Weeks',
			'string_months' => 'Month',
			'string_months2' => 'Months',
			'string_years' => 'Year',
			'string_years2' => 'Years',
			'string_hours' => 'Hour',
			'string_hours2' => 'Hours',
			'string_minutes' => 'Minute',
			'string_minutes2' => 'Minutes',
			'string_seconds' => 'Second',
			'string_seconds2' => 'Seconds',
			'font_size_count' => '72',
			'font_size_txt' => '14',
			'count_pos' => 'text-center',
			'color' => '#333',
			'color_txt' => '#333',
			'css' => ''
		), $atts ) );

		$count_frmt = $labels = $data_attr = $count = '';
		$labels = $string_years2 .','.$string_months2.','.$string_weeks2.','.$string_days2.','.$string_hours2.','.$string_minutes2.','.$string_seconds2;
		$labels2 = $string_years .','.$string_months.','.$string_weeks.','.$string_days.','.$string_hours.','.$string_minutes.','.$string_seconds;
		$countdown_opt = explode(",",$countdown_opts);				
			if(is_array($countdown_opt)){
				foreach($countdown_opt as $i => $opt){
					if($opt == "syear") $count_frmt .= 'Y';
					if($opt == "smonth") $count_frmt .= 'O';
					if($opt == "sweek") $count_frmt .= 'W';
					if($opt == "sday") $count_frmt .= 'D';
					if($opt == "shr") $count_frmt .= 'H';
					if($opt == "smin") $count_frmt .= 'M';
					if($opt == "ssec") $count_frmt .= 'S';	
				}
				$count = $i + 1;
			}

			$output = '<div class="veso_countdown '.$el_class.' animate-text">';
			if($datetime!=''){
				$output .='<div class="veso_countdown-div '.$count_pos.' veso_countdown-dateAndTime '.$veso_tz.' show'.$count.'" data-labels="'.$labels.'" data-labels2="'.$labels2.'"  data-terminal-date="'.$datetime.' '.$time.'" data-countformat="'.$count_frmt.'" data-time-zone="'.get_option('gmt_offset').'" data-time-now="'.str_replace('-', '/', current_time('mysql')).'" data-font-text="'.$font_size_txt.'" data-font-count="'.$font_size_count.'" data-color-text="'.$color_txt.'" '.$data_attr.' style="color: '.$color.'">'.$datetime.' '.$time.'</div>';
			}		

			$output .= '</div>';
			return $output;		
	}

	function datetimepicker($settings, $value) {
		$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
		$type = isset($settings['type']) ? $settings['type'] : '';
		$class = isset($settings['class']) ? $settings['class'] : '';
		$uni = uniqid();
		$output = '<div id="veso-date-time'.$uni.'" class="veso-datetime"><input data-format="yyyy/MM/dd hh:mm:ss" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" style="width:258px;" value="'.$value.'" /><div class="add-on" >  <i data-time-icon="Defaults-time" data-date-icon="Defaults-time"></i></div></div>';
		$output .= '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#veso-date-time'.$uni.' input").datepicker({
					language: "pt-BR",
					
				});
			})
			</script>';
		
		return $output;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Countdown", "veso-theme-plugin" ),
			"base" => "veso_countdown",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
			   		"type" => "datetimepicker",
					"class" => "",
					"heading" => __("Target Date For Countdown", 'veso-theme-plugin'),
					"param_name" => "datetime",
					"value" => "", 
					"description" => __("Date format (yyyy/mm/dd).", 'veso-theme-plugin'),
					"group" => __("General", 'veso-theme-plugin'),
				),	
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Target Time For Countdown", 'veso-theme-plugin'),
					"param_name" => "time",
					"value" => "", 
					"description" => __("Time format (hh:mm:ss).", 'veso-theme-plugin'),
					"group" => __("General", 'veso-theme-plugin'),
				),	
				array(
			   		"type" => "dropdown",
					"class" => "",
					"heading" => __("Countdown Timer Depends on", 'veso-theme-plugin'),
					"param_name" => "veso_tz",
					"value" => array(
						__("WordPress Defined Timezone", 'veso-theme-plugin') => "veso-wptz",
						__("User's System Timezone", 'veso-theme-plugin') => "veso-usrtz",
					),
					"group" => __("General", 'veso-theme-plugin'),
				),						
				array(
			   		"type" => "checkbox",
					"class" => "",
					"heading" => __("Select Time Units To Display In Countdown Timer", 'veso-theme-plugin'),
					"param_name" => "countdown_opts",
					"value" => array(
						__("Years",'veso-theme-plugin') => "syear",
						__("Months",'veso-theme-plugin') => "smonth",
						__("Weeks",'veso-theme-plugin') => "sweek",
						__("Days",'veso-theme-plugin') => "sday",										
						__("Hours",'veso-theme-plugin') => "shr",
						__("Minutes",'veso-theme-plugin') => "smin",
						__("Seconds",'veso-theme-plugin') => "ssec",										
					),
					'std' => 'syear,smonth,sweek,sday',
					"group" => __("General", 'veso-theme-plugin')
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Number Font Size", 'veso-theme-plugin'),
					"param_name" => "font_size_count",
					"value" => '72',
					"group" => __("General", 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Text Font Size", 'veso-theme-plugin'),
					"param_name" => "font_size_txt",
					"value" => '14',
					"group" => __("General", 'veso-theme-plugin'),
				),
				array(
			   		"type" => "dropdown",
					"class" => "",
					"heading" => __("Countdown position", 'veso-theme-plugin'),
					"param_name" => "count_pos",
					"value" => array(
						__("Left", 'veso-theme-plugin') => "text-left",
						__("Center", 'veso-theme-plugin') => "text-center",
						__("Right", 'veso-theme-plugin') => "text-right",
					),
					'std' => 'text-center',
					"group" => __("General", 'veso-theme-plugin'),
				),			
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Extra Class", 'veso-theme-plugin'),
					"param_name" => "el_class",
					"value" => "",
					"description" => __("Extra Class for the Wrapper.", 'veso-theme-plugin'),
					"group" => __("General", 'veso-theme-plugin'),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Number color", 'veso-theme-plugin'),
					"param_name" => "color",		
					"value" => "#333",
					'group' => __('Colors', 'veso-theme-plugin'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "color_txt",		
					"value" => "#333",	
					'group' => __('Colors', 'veso-theme-plugin'),	
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Day (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_days",
					"value" => "Day",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Days (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_days2",
					"value" => "Days",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Week (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_weeks",
					"value" => "Week",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Weeks (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_weeks2",
					"value" => "Weeks",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Month (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_months",
					"value" => "Month",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Months (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_months2",
					"value" => "Months",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Year (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_years",
					"value" => "Year",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Years (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_years2",
					"value" => "Years",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Hour (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_hours",
					"value" => "Hour",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Hours (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_hours2",
					"value" => "Hours",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Minute (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_minutes",
					"value" => "Minute",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Minutes (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_minutes2",
					"value" => "Minutes",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),							
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Second (Singular)", 'veso-theme-plugin'),
					"param_name" => "string_seconds",
					"value" => "Second",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),
				array(
			   		"type" => "textfield",
					"class" => "",
					"heading" => __("Seconds (Plural)", 'veso-theme-plugin'),
					"param_name" => "string_seconds2",
					"value" => "Seconds",
					"group" => __("Strings Translation", 'veso-theme-plugin')
				),



			),
		));
	}

}