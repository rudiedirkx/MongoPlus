<?php

function repeat( $amount, Closure $callback, $arguments = array() ) {
	for ( $i=0; $i<$amount; $i++ ) {
		call_user_func_array($callback, $arguments);
	}
}


