<?php

if ( ! function_exists( 'ddd' ) )
{
	function ddd()
	{
		echo '<pre>';
		$r = array();
		$args = func_get_args();
		foreach( $args as $arg )
			$r[] = trim( htmlspecialchars( var_export( $arg, true ) ), "'" );
		echo implode( ' ', $r );
		echo '</pre>';
	}
}

require_once( __DIR__ . '/../include.php' );
