<?php
if(function_exists('openssl_random_pseudo_bytes')){
	echo base64_encode(openssl_random_pseudo_bytes(128));
}else{
	echo base64_encode(mcrypt_create_iv(128, MCRYPT_DEV_URANDOM));
}
