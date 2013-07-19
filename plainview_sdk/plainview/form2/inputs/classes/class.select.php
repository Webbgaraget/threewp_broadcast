<?php

namespace plainview\form2\inputs;

/**
	@brief		Select input.
	@author		Edward Plainview <edward@plainview.se>
	@copyright	GPL v3
	@version	20130524
**/
class select
	extends input
{
	use traits\options;
	use traits\size;
	use traits\value
	{
		traits\options::use_post_value insteadof traits\value;
		traits\options::value insteadof traits\value;
	}

	public $self_closing = false;
	public $tag = 'select';

	public $_value = array();

	public function __toString()
	{
		return $this->indent() . $this->display_label() . $this->display_input();
	}

	public function display_input()
	{
		$input = clone( $this );

		$input->css_class( 'select' );

		if ( $input->is_required() )
			$input->css_class( 'required' );

		// Multiples require the [] part that isn't really part of the name.
		if ( $input->is_multiple() )
			$input->set_attribute( 'name', $input->make_name() );

		$r = $input->indent() . $input->open_tag() . "\n";
		foreach( $input->options as $option )
		{
			$option = clone( $option );
			if ( is_a( $option, 'plainview\\form2\\inputs\\select_optgroup' ) )
				$r .= $option;
			else
			{
				$option->clear_attribute( 'name' );
				if ( in_array( $option->get_attribute( 'value' ), $input->_value ) )
					$option->check( true );
				$r.= $option;
			}
		}
		$r .= $input->indent() . $input->close_tag() . "\n";
		return $r;
	}

	/**
		@brief		Returns the input's value from the _POST variable.
		@details	Will strip off slashes before returning the value.
		@return		string		The value of the _POST variable. If no value was in the post, null is returned.
		@see		use_post_value()
		@since		20130524
	**/
	public function get_post_value()
	{
		$name = $this->make_name();
		if ( $this->is_multiple() )
			$name = substr( $name, 0, -2 );
		return $this->form()->get_post_value( $name );
	}
	/**
		@brief		Return if the user may select multiple options.
		@return		bool		True if the multiple attribute is set.
		@since		20130506
	**/
	public function is_multiple()
	{
		return $this->get_boolean_attribute( 'multiple' );
	}

	/**
		@brief		Make the name of the input and maybe correct for multiplicity.
		@return		string		The HTML name of the input.
	**/
	public function make_name()
	{
		$name = parent::make_name();
		if ( $this->is_multiple() )
			$name .= '[]';
		return $name;
	}

	/**
		@brief		Allow the user to select several options.
		@param		bool		$multiple		True if the user is allowed to select multiple options.
		@return		$this		This object.
		@since		20130524
	**/
	public function multiple( $multiple = true )
	{
		return $this->set_boolean_attribute( 'multiple', $multiple );
	}

	public function new_option( $o )
	{
		$input = new select_option( $o->container, $o->container->get_attribute( 'name' ) );
		return $input;
	}

	/**
		@brief		Create / return an optgroup.
		@param		string		$name		Name of the optgroup to create / return.
		@return		optgroup		Created or returned optgroup.
		@since		20130524
	**/
	public function optgroup( $name )
	{
		if ( isset( $this->inputs[ $name ] ) )
			return $this->inputs[ $name ];
		$input = new select_optgroup( $this, $name );
		$this->options[ $name ] = $input;
		return $input;
	}

	/**
		@brief		Set the value of this select.
		@details	Several parameters can be given and they will be merged into an array.
		@param		mixed		$value		Value to set.
		@return		$this		This object.
	**/
	public function value( $value, $value2 = null )
	{
		$args = func_get_args();
		if ( count( $args ) > 1 )
			$value = $args;
		if ( ! is_array( $value ) )
			$value = array( $value );
		$this->_value = $value;
		return $this;
	}
}

