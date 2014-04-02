<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	Class extension_author_gravatar extends Extension {
	
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'appendAssets'
				)
			);
		}
	
		public function appendAssets($context) {

			// add stylesheet with changes to header
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/author_gravatar/assets/author_gravatar.admin.css', 'screen', 100);

			// create gravatar image
			$img = new XMLElement('img', null, array(
				'src' => $this->getGravatar($this->getAuthorEmail(), 30), 
				'class' => 'gravatar'
			));

			// create anchor element as parent to the gravatar image
			$a = new XMLElement('a', $img, array(
				'href' => SYMPHONY_URL . '/system/authors/edit/' . Administration::instance()->Author->get('id') . '/',
				'data-id' => Administration::instance()->Author->get('id'),
				'data-name' => Administration::instance()->Author->get('first_name'),
				'data-type' => Administration::instance()->Author->get('user_type'),
				'class' => 'gravatar'
			));
			
			// append anchor / gravatar image to backend header element
			Administration::instance()->Page->Header->appendChild($a);
		}

		/**
		 * Get the current authors email address
		 * @return String containing the email address or empty string
		 */
		public static function getAuthorEmail() {
			$author = Administration::instance()->Author;
			if (isset($author)) { 
				return $author->get('email'); 
			} else { 
				return ''; 
			}
		}

		/**
		 * Get either a Gravatar URL or complete image tag for a specified email address.
		 * @param string $email The email address
		 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
		 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
		 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
		 * @param boole $img True to return a complete IMG tag False for just the URL
		 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
		 * @return String containing either just a URL or a complete image tag
		 */
		public static function getGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
		    $url = 'http://www.gravatar.com/avatar/';
		    $url .= md5( strtolower( trim( $email ) ) );
		    $url .= "?s=$s&d=$d&r=$r";
		    if ( $img ) {
		        $url = '<img src="' . $url . '"';
		        foreach ( $atts as $key => $val )
		            $url .= ' ' . $key . '="' . $val . '"';
		        $url .= ' />';
		    }
		    return $url;
		}
	
	}
