<?php
namespace Controller;

use Interfaces\ModelInterface as bbb;

class IndexController
{

	public function __construct( bbb $aaaaa, array $abcd = null ) {
		dump( $aaaaa->connection() );
		dump( $abcd );
	}

	public function connection() {
		
	}
	
	public function testMethod( bbb $sadf, array $abc = null ) {
		
		$sadf->connection();
		dump( $abc );
	}

	public function say() {
		echo 'say say say IndexController';
	}


}