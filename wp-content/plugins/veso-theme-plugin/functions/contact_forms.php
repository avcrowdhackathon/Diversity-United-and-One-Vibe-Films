<?php
function veso_contact_form_markup() {
			$post_id = wp_insert_post(array (
				'post_type' => 'wpcf7_contact_form',
				'post_title' => 'Veso Contact Form',
				'post_content' => '<div class="contact-details form-fieldset">
  <div class="contact-text name contact-input veso-input input-required">
     <label>Name</label>[text* your-name]
  </div> 
  <div class="contact-text email contact-input veso-input input-required">
     <label>E-mail</label>[email* your-email]
  </div>        					
</div>
<div class="form-fieldset">
  <div class="contact-text phone contact-input veso-input">
      <label>Phone no</label>[tel your-phone]
  </div>  
</div>
<div class="message form-fieldset">
  <div class="contact-textarea message contact-input veso-input">
      <label>Your message</label>[textarea your-message]
  </div>  
</div>
<div class="form-submit text-right">
  [submit "Send"]
</div>
1
Veso "[your-subject]"
[your-name] <wordpress@veso.yosoftware.com>
wordpress@veso.yosoftware.com
From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)
Reply-To: [your-email]




Veso "[your-subject]"
Veso <wordpress@veso.yosoftware.com>
[your-email]
Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)
Reply-To: '.get_bloginfo('admin_email').'



Thank you for your message. It has been sent.
There was an error trying to send your message. Please try again later.
One or more fields have an error. Please check and try again.
There was an error trying to send your message. Please try again later.
You must accept the terms and conditions before sending your message.
The field is required.
The field is too long.
The field is too short.
The date format is incorrect.
The date is before the earliest one allowed.
The date is after the latest one allowed.
There was an unknown error uploading the file.
You are not allowed to upload files of this type.
The file is too big.
There was an error uploading the file.
The number format is invalid.
The number is smaller than the minimum allowed.
The number is larger than the maximum allowed.
The answer to the quiz is incorrect.
Your entered code is incorrect.
The e-mail address entered is invalid.
The URL is invalid.
The telephone number is invalid.',
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
			));
		
		if($post_id) {
			add_post_meta($post_id, '_form', '<div class="contact-details form-fieldset">
  <div class="contact-text name contact-input veso-input input-required">
     <label>Name</label>[text* your-name]
  </div> 
  <div class="contact-text email contact-input veso-input input-required">
     <label>E-mail</label>[email* your-email]
  </div>        					
</div>
<div class="form-fieldset">
  <div class="contact-text phone contact-input veso-input">
      <label>Phone no</label>[tel your-phone]
  </div>  
</div>
<div class="message form-fieldset">
  <div class="contact-textarea message contact-input veso-input">
      <label>Your message</label>[textarea your-message]
  </div>  
</div>
<div class="form-submit text-right">
  [submit "Send"]
</div>');
			add_post_meta($post_id, '_mail', array (
  'active' => true,
  'subject' => 'Veso "[your-subject]"',
  'sender' => '[your-name] <wordpress@veso.yosoftware.com>',
  'recipient' => ''.get_bloginfo('admin_email').'',
  'body' => 'From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)',
  'additional_headers' => 'Reply-To: [your-email]',
  'attachments' => '',
  'use_html' => false,
  'exclude_blank' => false,
) );
			add_post_meta($post_id, '_mail2', array(
				  'active' => false,
				  'subject' => 'Veso "[your-subject]"',
				  'sender' => 'Veso <wordpress@veso.yosoftware.com>',
				  'recipient' => '[your-email]',
				  'body' => 'Message Body:
				[your-message]

				-- 
				This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)',
				  'additional_headers' => 'Reply-To: '.get_bloginfo('admin_email').'',
				  'attachments' => '',
				  'use_html' => false,
				  'exclude_blank' => false,
				) );
			add_post_meta($post_id, '_messages', array (
  				'mail_sent_ok' => 'Thank you for your message. It has been sent.',
  				'mail_sent_ng' => 'There was an error trying to send your message. Please try again later.',
  				'validation_error' => 'One or more fields have an error. Please check and try again.',
  				'spam' => 'There was an error trying to send your message. Please try again later.',
  				'accept_terms' => 'You must accept the terms and conditions before sending your message.',
  				'invalid_required' => 'The field is required.',
  				'invalid_too_long' => 'The field is too long.',
  				'invalid_too_short' => 'The field is too short.',
  				'invalid_date' => 'The date format is incorrect.',
  				'date_too_early' => 'The date is before the earliest one allowed.',
  				'date_too_late' => 'The date is after the latest one allowed.',
  				'upload_failed' => 'There was an unknown error uploading the file.',
  				'upload_file_type_invalid' => 'You are not allowed to upload files of this type.',
  				'upload_file_too_large' => 'The file is too big.',
  				'upload_failed_php_error' => 'There was an error uploading the file.',
  				'invalid_number' => 'The number format is invalid.',
  				'number_too_small' => 'The number is smaller than the minimum allowed.',
  				'number_too_large' => 'The number is larger than the maximum allowed.',
  				'quiz_answer_not_correct' => 'The answer to the quiz is incorrect.',
  				'captcha_not_match' => 'Your entered code is incorrect.',
  				'invalid_email' => 'The e-mail address entered is invalid.',
  				'invalid_url' => 'The URL is invalid.',
  				'invalid_tel' => 'The telephone number is invalid.',
			));
			add_post_meta($post_id, '_additional_settings', '');
			add_post_meta($post_id, '_locale', get_locale() );
		}
	}

function veso_horizontal_contact_form_markup() {
      $post_id = wp_insert_post(array (
        'post_type' => 'wpcf7_contact_form',
        'post_title' => 'Veso Horizontal Contact Form',
        'post_content' => '<div class="form-fieldset">
  <div class="contact-text name contact-input veso-input input-required">
     <label>Name</label>[text* your-name]
  </div> 
  <div class="contact-text email contact-input veso-input input-required">
     <label>E-mail</label>[email* your-email]
  </div>   
<div class="contact-text phone contact-input veso-input">
      <label>Phone no</label>[tel your-phone]
  </div>       
<div class="form-submit veso-input hor-submit">
  [submit "Send"]
</div>          
</div>
1
Veso "[your-subject]"
[your-name] <wordpress@veso.yosoftware.com>
wordpress@veso.yosoftware.com
From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)
Reply-To: [your-email]




Veso "[your-subject]"
Veso <wordpress@veso.yosoftware.com>
[your-email]
Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)
Reply-To: '.get_bloginfo('admin_email').'



Thank you for your message. It has been sent.
There was an error trying to send your message. Please try again later.
One or more fields have an error. Please check and try again.
There was an error trying to send your message. Please try again later.
You must accept the terms and conditions before sending your message.
The field is required.
The field is too long.
The field is too short.
The date format is incorrect.
The date is before the earliest one allowed.
The date is after the latest one allowed.
There was an unknown error uploading the file.
You are not allowed to upload files of this type.
The file is too big.
There was an error uploading the file.
The number format is invalid.
The number is smaller than the minimum allowed.
The number is larger than the maximum allowed.
The answer to the quiz is incorrect.
Your entered code is incorrect.
The e-mail address entered is invalid.
The URL is invalid.
The telephone number is invalid.',
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
      ));
    
    if($post_id) {
      add_post_meta($post_id, '_form', '<div class="form-fieldset">
  <div class="contact-text name contact-input veso-input input-required">
     <label>Name</label>[text* your-name]
  </div> 
  <div class="contact-text email contact-input veso-input input-required">
     <label>E-mail</label>[email* your-email]
  </div>   
<div class="contact-text phone contact-input veso-input">
      <label>Phone no</label>[tel your-phone]
  </div>       
<div class="form-submit veso-input hor-submit">
  [submit "Send"]
</div>          
</div>');
      add_post_meta($post_id, '_mail', array (
  'active' => true,
  'subject' => 'Veso "[your-subject]"',
  'sender' => '[your-name] <wordpress@veso.yosoftware.com>',
  'recipient' => ''.get_bloginfo('admin_email').'',
  'body' => 'From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)',
  'additional_headers' => 'Reply-To: [your-email]',
  'attachments' => '',
  'use_html' => false,
  'exclude_blank' => false,
) );
      add_post_meta($post_id, '_mail2', array(
          'active' => false,
          'subject' => 'Veso "[your-subject]"',
          'sender' => 'Veso <wordpress@veso.yosoftware.com>',
          'recipient' => '[your-email]',
          'body' => 'Message Body:
        [your-message]

        -- 
        This e-mail was sent from a contact form on Veso (http://veso.yosoftware.com/2)',
          'additional_headers' => 'Reply-To: '.get_bloginfo('admin_email').'',
          'attachments' => '',
          'use_html' => false,
          'exclude_blank' => false,
        ) );
      add_post_meta($post_id, '_messages', array (
          'mail_sent_ok' => 'Thank you for your message. It has been sent.',
          'mail_sent_ng' => 'There was an error trying to send your message. Please try again later.',
          'validation_error' => 'One or more fields have an error. Please check and try again.',
          'spam' => 'There was an error trying to send your message. Please try again later.',
          'accept_terms' => 'You must accept the terms and conditions before sending your message.',
          'invalid_required' => 'The field is required.',
          'invalid_too_long' => 'The field is too long.',
          'invalid_too_short' => 'The field is too short.',
          'invalid_date' => 'The date format is incorrect.',
          'date_too_early' => 'The date is before the earliest one allowed.',
          'date_too_late' => 'The date is after the latest one allowed.',
          'upload_failed' => 'There was an unknown error uploading the file.',
          'upload_file_type_invalid' => 'You are not allowed to upload files of this type.',
          'upload_file_too_large' => 'The file is too big.',
          'upload_failed_php_error' => 'There was an error uploading the file.',
          'invalid_number' => 'The number format is invalid.',
          'number_too_small' => 'The number is smaller than the minimum allowed.',
          'number_too_large' => 'The number is larger than the maximum allowed.',
          'quiz_answer_not_correct' => 'The answer to the quiz is incorrect.',
          'captcha_not_match' => 'Your entered code is incorrect.',
          'invalid_email' => 'The e-mail address entered is invalid.',
          'invalid_url' => 'The URL is invalid.',
          'invalid_tel' => 'The telephone number is invalid.',
      ));
      add_post_meta($post_id, '_additional_settings', '');
      add_post_meta($post_id, '_locale', get_locale() );
    }
  }