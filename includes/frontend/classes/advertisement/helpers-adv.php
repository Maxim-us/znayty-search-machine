<?php

// hide phone
function mx_hide_phone( $phone ) {

	if( ! is_user_logged_in() ) {

		global $post;

		echo '<div class="mx-phone-hidden" title="Щоб побачити номер - авторизуйтесь!">+3 80 ... <a href="/my-account/?adv_parrent=mxzsm_adv_need&adv_slug=' . $post->post_name . '">Увійти</a></div>';

	} else {

		echo $phone;

	}	

}

// hide social
function mx_hide_social( $url ) {

	if( ! is_user_logged_in() ) {

		global $post;

		echo '<div class="mx-phone-social" title="Щоб побачити лінк - авторизуйтесь!"><a href="/my-account/?adv_parrent=mxzsm_adv_need&adv_slug=' . $post->post_name . '">Увійти</a></div>';

	} else {

		echo '<div class="mx-phone-social" title="Щоб побачити лінк - авторизуйтесь!"><a href="' . $url . '">' . $url . '</a></div>';

	}	

}

// display avatar
function mx_display_avatar() {

	global $post;

	$author_id = $post->post_author;	

	$avatar = get_avatar( $author_id, 96 );

	echo $avatar;

	$author = get_user_meta( $author_id, 'nickname', true );

	$author_first_name = get_user_meta( $author_id, 'first_name', true );

	$author_last_name = get_user_meta( $author_id, 'last_name', true );

	if( $author_first_name !== '' ) {

		$author = $author_first_name . ' ' . $author_last_name;

	}

	echo '<span class="mx_user_name_adv">' . $author . '</span>';

}