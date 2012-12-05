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
		
		// Set the filters for the comment notification email
		add_filter( 'comment_notification_headers', array( $this, 'email_headers' ) );
		add_filter( 'comment_notification_subject', array( $this, 'email_subject' ) );
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
    	// TODO
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
    	
    	// First, let's get the title of the post from the incoming comment ID
    	$comment = get_comment( $comment_id );
		$post_title = get_the_title( $comment->comment_post_ID );
    	
    	// Next, create the subject line
    	$subject = __( "[", 'custom-comment-email-locale' ) . $post_title . __( "]", 'custom-comment-email-locale' );
    	$subject .= "&nbsp;";
    	$subject .=  __( "Hey There! Looks like you've got a new comment!", 'custom-comment-email-locale' );
    	
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
    	// TODO
	} // end filter_method_name
  
} // end class

new Custom_Comment_Email();