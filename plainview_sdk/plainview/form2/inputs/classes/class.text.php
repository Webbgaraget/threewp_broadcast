<?php

namespace plainview\form2\inputs;

/**
	@brief		Text / textfield input.
	@details	Is the parent class of many text-related inputs.
	@author		Edward Plainview <edward@plainview.se>
	@copyright	GPL v3
	@version	20130712
**/
class text
	extends input
{
	use traits\minlength;
	use traits\maxlength;
	use traits\placeholder;
	use traits\size;
	use traits\value;

	public $lowercase = false;
	public $trim = false;
	public $type = 'text';
	public $uppercase = false;

	/**
		@brief		Require that this textfield's value be trimmed when set.
		@param		bool		$value		True to trim the value when getting it from the POST.
		@since		20130712
	**/
	public function trim( $value = true )
	{
		$this->trim = $value;
		$this->add_value_filter( 'text.trim', function( $value )
		{
			if ( $this->trim )
				$value = trim( $value );
			return $value;
		});
		return $this;
	}

	/**
		@brief		Require that this textfield's value be lowercased.
		@param		bool		$value		True to lowercase the value.
		@since		20130718
	**/
	public function lowercase( $value = true )
	{
		$this->lowercase = $value;
		$this->add_value_filter( 'text.lowercase', function( $value )
		{
			$value = mb_strtolower( $value, 'UTF-8' );
			return $value;
		});
		return $this;
	}

	/**
		@brief		Require that this textfield's value be uppercased.
		@param		bool		$value		True to uppercase the value.
		@since		20130718
	**/
	public function uppercase( $value = true )
	{
		$this->uppercase = $value;
		$this->add_value_filter( 'text.uppercase', function( $value )
		{
			$value = mb_strtoupper( $value, 'UTF-8' );
			return $value;
		});
		return $this;
	}
}
