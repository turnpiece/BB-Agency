<?php

/*
Plugin Name: Turnpiece Fix Protocol
Plugin URI: http://turnpiece.com
Description: Change http => https within posts
Version: 0.0.1
Author: Paul Jenkins @ Turnpiece
Author URI: http://www.turnpiece.com
License: GPLv2 or later
*/

require 'class.fix-protocol.php';

new TP_Fix_Protocol;