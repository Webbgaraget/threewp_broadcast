<?php

namespace threewp_broadcast\maintenance;

class controller
{
	/**
		@brief		The ThreeWP Broadcast object.
		@since		20131101
	**/
	public $broadcast;

	public function __construct()
	{
		$this->broadcast = \threewp_broadcast\ThreeWP_Broadcast::instance();
	}

	public function __toString()
	{
		$this->data = data::load();
		$r = '';

		if ( isset( $_GET[ 'check' ] ) )
		{
			$id = $_GET[ 'check' ];
			if ( $this->data->checks->has( $id ) )
			{
				$check = $this->data->checks->get( $id );
				if ( $check->step == 'start' )
					$r .= $check->init();
				$r .= $check->step();
				$this->data->save();
			}
			else
				wp_die( sprintf( 'Check %s does not exist!', $id ) );
		}
		else
			$r = $this->get_table();

		return $r;
	}

	/**
		@brief		Return the table showing all of the check types.
		@since		20131102
	**/
	public function get_table()
	{
		// Reset the data.
		$this->data = $this->data->reset();

		$form = $this->broadcast->form2();
		$r = '';
		$table = $this->broadcast->table();

		$row = $table->head()->row();
		$row->th()->text( 'Check' );
		$row->th()->text( 'Description' );

		foreach( $this->data->checks as $check )
		{
			$check->next_step( 'start' );
			$row = $table->body()->row();
			$name = sprintf( '<a href="%s">%s</a>',
				add_query_arg( [ 'check' => $check->get_id() ] ),
				$check->get_name()
			);
			$row->td()->text( $name );
			$row->td()->text( $check->get_description() );
		}

		$this->data->save();

		$r .= $this->broadcast->p( 'This <strong>experimental</strong> function allows the broadcast database to be checked and repaired. Make a backup of your Wordpress installation before using the repair functions.' );

		$r .= $this->broadcast->p( 'Below is a table of available checks / tools. Click on the name of the check to use it.' );

		$r .= $form->open_tag();
		$r .= $table;
		$r .= $form->close_tag();

		return $r;
	}
}
