<?php

// function aliases

function __($tt) {
	echo \Admin\Utils::translate($tt);
}

function _snippet($alias, $args) {
	return forward_static_call_array(['\Admin\Snippet', $alias], $args);
}