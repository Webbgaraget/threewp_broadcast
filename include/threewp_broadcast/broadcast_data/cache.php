<?php

namespace threewp_broadcast\broadcast_data;

use threewp_broadcast\BroadcastData;

/**
	@brief		Cache for broadcast data
	@since		20131009
**/
class cache
	extends \plainview\collections\collection
{
	public function expect( $blog_id, $post_ids )
	{
		if ( ! is_array( $post_ids ) )
			$post_ids = [ $post_ids ];

		$missing_post_ids = [];
		foreach( $post_ids as $post_id )
		{
			$key = $this->key( $blog_id, $post_id );
			if ( $this->has( $key ) )
				continue;
			$missing_post_ids []= $post_id;
		}

		// Are we missing anything?
		if ( count( $missing_post_ids ) < 1 )
			return;

		// Fetch them!
		$results = \threewp_broadcast\ThreeWP_Broadcast::instance()->sql_get_broadcast_datas( $blog_id, $missing_post_ids );

		// Since not all requested post IDs have broadcast data, foreach the missing post ids, not the results, and add them to the cache.
		foreach( $missing_post_ids as $post_id )
		{
			$data = null;
			foreach( $results as $result )
			{
				if ( $result[ 'post_id' ] == $post_id )
				{
					$data = $result[ 'data' ];
					break;
				}
			}

			if ( ! $data )
				$data = new BroadcastData;

			$key = $this->key( $blog_id, $post_id );
			$this->set( $key, $data );
		}
		return $this;
	}

	public function expect_from_wp_query()
	{
		global $wp_query;
		$blog_id = get_current_blog_id();
		$post_ids = [];
		foreach( $wp_query->posts as $post )
			$post_ids []= $post->ID;
		$this->expect( $blog_id, $post_ids );
		return $this;
	}

	public function get_for( $blog_id, $post_id )
	{
		$key = $this->key( $blog_id, $post_id );

		if ( ! $this->has( $key ) )
		{
			// Retrieve the post data for this solitary post.
			$results = \threewp_broadcast\ThreeWP_Broadcast::instance()->sql_get_broadcast_datas( $blog_id, $post_id );
			if ( count( $results ) == 1 )
			{
				$results = reset( $results );
				$bcd = $results[ 'data' ];
			}
			else
				$bcd = new BroadcastData;
			$this->set_for( $blog_id, $post_id, $bcd );
		}

		return $this->get( $key );
	}

	public function key( $blog_id, $post_id )
	{
		return sprintf( '%s_%s', $blog_id, $post_id );
	}

	public function set_for( $blog_id, $post_id, $broadcast_data )
	{
		$key = $this->key( $blog_id, $post_id );
		$this->set( $key, $broadcast_data );
	}

}
