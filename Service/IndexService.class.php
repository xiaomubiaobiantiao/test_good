<?php
namespace Service;

include( 'Interfaces\ModelInterface.class.php' );
use Interfaces\ModelInterface;

class IndexService implements ModelInterface
{

	public function connection() {
		echo 'connect mysql success!';
	}



}