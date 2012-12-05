<?php
/*
Plugin Name: Custom Comment Emails
Plugin URI: http://github.com/tommcfarlin/custom-comment-emails/
Description: A plugin used to demonstrate how to create custom comment email notifications. The corresponding series of articles will run on Envato's TutsPlus Network.
Version: 1.0
Author: Tom McFarlin
Author URI: http://tommcfarlin.com
License:

  Copyright 2012 Tom McFarlin (tom@tommcfarlin.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class Custom_Comment_Email {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );
		
		/* Set the filters for comment approval and the comment notification email.
		 * For purposes of this example plugin, these will be the same email.
		 * Though in a production environment, you'd naturally want to include the typical
		 * 'Approve,' 'Spam,' and 'Trash' links.
		 */
		 
		// Moderation
		add_filter( 'comment_moderation_headers', array( $this, 'email_headers' ) );
		add_filter( 'comment_moderation_subject', array( $this, 'email_subject' ), 10, 2 );
		add_filter( 'comment_moderation_text', array( $this, 'email_text' ), 10, 2 );
		
		// Notifications
		add_filter( 'comment_notification_headers', array( $this, 'email_headers' ) );
		add_filter( 'comment_notification_subject', array( $this, 'email_subject' ), 10, 2 );
	    add_filter( 'comment_notification_text', array( $this, 'email_text' ), 10, 2 );

	} // end constructor
	
	/*--------------------------------------------*
	 * Localization
	 *--------------------------------------------*/
	
	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		load_plugin_textdomain( 'custom-comment-email-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	} // end plugin_textdomain
	
	/*--------------------------------------------*
	 * Filters
	 *--------------------------------------------*/
	
	/**
	 * Sets the headers for the email being sent for the comment notification email.
	 *
	 * @since	1.0
	 */
	function email_headers() {
    	add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
	} // end action_method_name

	/**
	 * Creates the customized subject for the comment notification email.
	 *
	 * @param	string	$subject	The content of the subject
	 * @param	int		$comment_id	The ID of the comment for which this subject is being sent
	 * @return						The subject line for the email
	 * @since	1.0
	 */	
	function email_subject( $subject, $comment_id ) {
    	
    	// Create the subject line in the following format: "[Post Title] Hey There - Looks like you've got a new comment!"
    	$subject = __( "[", 'custom-comment-email-locale' ) . $this->get_post_title( $comment_id ) . __( "]", 'custom-comment-email-locale' );
    	$subject .= " ";
    	$subject .=  __( "Hey There - Looks like you've got a new comment!", 'custom-comment-email-locale' );
    	
    	return $subject;
    	
	} // end filter_method_name

	/**
	 * Creates a customized, styled email used to notify users that they have a new comment.
	 *
	 * @param	string	$message		The content of the email
	 * @param	int		$comment_id		The ID of the comment being left
	 * @return							The customized body content of the email
	 * @since 1.0
	 */
	function email_text( $message, $comment_id ) {

    	// Retrieve the comment
    	$comment = get_comment( $comment_id );
    	
    	// Define the header
    	$message = '<h1 style="font-size 1.5em; display: block;">';
    		$message .= __( 'Comment For ', 'custom-comment-email-locale' );
    		$message .= $this->get_post_title( $comment_id );
    	$message .= '</h1>';
    	
    	$message .= '<div style="width: 100%; border-top: 1px solid #ededed;margin-bottom: 12px;">';
    		$message .= '<h3>' . __( 'The original contents of this email read:', 'custom-comment-email-locale' ) . '</h3>';
    		$message .= $comment->comment_content;
    	$message .= '</div>';
    	
    	// Determine what type of comment this is:
    	$comment_type = __( 'normal comment.', 'custom-comment-email-locale' );
    	if( '' != $comment->comment_type ) {
	    	$comment_type = __( 'trackback or a pingback.', 'custom-comment-email-locale' );
    	} // end if
    	
    	// And set the footer
    	$message .= '<div style="background: #ededed; border-top: 1px solid #ededed;padding: 4px; text-align: center;">';
			$message .= __( 'This comment was left by ', 'custom-comment-email-locale' ) . '<strong><a href="mailto:"' . $comment->comment_author_email . '">' . $comment->comment_author_email . '</a></strong>.';
			$message .= __( ' | ', 'custom-comment-email-locale' );
			$message .= __( 'This is a ', 'custom-comment-email-locale' ) . '<strong>' . $comment_type . '</strong>.';
    	$message .= '</div>';
    	
    	return $message;
    	
	} // end filter_method_name
	
	/*--------------------------------------------*
	 * Helper Functions
	 *--------------------------------------------*/
	
	/**
	 * Retrieves the ID of the post associated with this comment.
	 * 
	 * @param	int		$comment_id	The ID of the comment that we're using to get the post title
	 * @return	string				The title of the comment's post
	 * @since	1.0
	 */
	private function get_post_title( $comment_id ) { 
		
    	$comment = get_comment( $comment_id );
    	return get_the_title( $comment->comment_post_ID );
		
	} // end get_post_title
  
} // end class

new Custom_Comment_Email();