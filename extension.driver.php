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
			$author = null;
			if (is_callable(array('Symphony', 'Author'))) {
				$author = Symphony::Author();
			} else {
				$author = Administration::instance()->Author;
			}

			// add stylesheet with changes to header
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/author_gravatar/assets/author_gravatar.admin.css', 'screen', 100);

			// create gravatar image
			$img = new XMLElement('img', null, array(
				'src' => $this->getGravatar($author->get('email'), 40),
				'class' => 'gravatar'
			));

			// create anchor element as parent to the gravatar image
			$a = new XMLElement('a', $img, array(
				'href' => SYMPHONY_URL . '/system/authors/edit/' . $author->get('id') . '/',
				'data-id' => $author->get('id'),
				'data-name' => $author->get('first_name'),
				'data-type' => $author->get('user_type'),
				'class' => 'gravatar'
			));

			// append anchor / gravatar image to backend header element
			Administration::instance()->Page->Session->appendChild($a);
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
		    $url = '//www.gravatar.com/avatar/';
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
