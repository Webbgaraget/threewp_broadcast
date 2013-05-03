plainview_sdk
=============

A toolkit of commonly used classes and functions, including Wordpress and Drupal SDKs.

Requirements
------------

* PHP v5.4 for traits support.

Standalone usage
----------------

Include the sdk.php file.

	require_once( 'plainview_sdk/sdk.php' );
	
The SDK's function can now be access statically:

	if ( \plainview\base::is_email( 'test@test.com' ) )
		echo 'Valid e-mail address!';

Or by dynamically instancing the base:

	class sdk_test extends \plainview\base
	{
	}
	
	$test = new sdk_test();
	if ( $test->is_email( 'test@test.com' ) )
		echo 'Valid e-mail address!';

Wordpress SDK
-------------

See the below list for examples of how live plugins use the SDK:

* [ThreeWP Broadcast](http://wordpress.org/extend/plugins/threewp-broadcast/)

Third party plugins used
-------

* [PHP Mailer](http://phpmailer.sourceforge.net)

License
-------

GPL v3

Contact
-------

The author can be contacted at: edward@plainview.se
