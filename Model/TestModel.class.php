<?php
namespace Model;

// include( 'Interfaces\ModelInterface.class.php' );
use Interfaces\ModelInterface;

class TestModel implements ModelInterface
{

	public function connection() {
		echo 'connect sqlserver success!';
	}



}