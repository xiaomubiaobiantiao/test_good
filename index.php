<?php
error_reporting(E_ALL ^ E_NOTICE);
include( 'Container/Container.class.php' );
include( 'Controller/IndexController.class.php' );
include( 'Service/IndexService.class.php' );
include( 'Model/TestModel.class.php' );
include( 'dump.php' );

use Container\Container;
use Controller\IndexController;
use Service\IndexService;

// $IndexService = new IndexService();
// $IndexService->connection();
$container = new Container();

// $dataService = 'sqlserve';
// $dataService = 'mysql';
// $dataService = 'oracle';

// new IndexController();


// 测试二 三
$container->bind( 'Interfaces\ModelInterface', 'Service\IndexService' );
// $container->bind( 'Interfaces\ModelInterface', 'Model\TestModel' );

// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\IndexService' ));
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Model\TestModel' ));


// 测试一
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\aa' ));
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\bb' ));


// $container->bind( 'Controller\IndexController', 'Interfaces\ModelInterface' );
// $container->bind( 'Controller\IndexController', 'Service\IndexService' );
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\IndexService' ) );
// $container->bind( 'Controller\IndexController', array( 'Interdfaces\ModelInterface', 'Model\TestModel' ) );

// $container->bind( 'ModelInterface', 'Interfaces\ModelInterface' );

$container->setAlias( 'ModelInterface', 'Interfaces\ModelInterface' );
$container->setAlias( 'IndexService', 'Service\IndexService' );

// dump( $container->getAlias() );
dump( $container->getBinds() );
// dump( $container->getAllAlias() );

$container->make( 'IndexController' );
// $container->make( 'IndexController', 'IndexService' );


// 绑定接口的用法
// $container->make( 'Controller\IndexController', 'Service\IndexService', array( 'aabbcc' ) );
// $IndexController = $container->make( 'IndexController', 'IndexService', array('sadf') );
// $container->make( 'Controller\IndexController', 'Model\TestModel' );
// dump( $IndexController );
// $IndexController->say();
// $container->make( 'IndexController', 'IndexService' );
// $container->make( 'IndexController', 'TestModel' );

// $container->makeWith();
// $container->make( 'Controller\IndexController', 'Model\TestModel', array('ccc') );
// $container->makeWith( 
// 	'Controller\IndexController', 
// 	'testMethod',
// 	'Service\IndexService', 
// 	array('bbb'), 
// 	'Model\TestModel', 
// 	array('ccc') 
// );

// $container->makeWith( 
// 	'IndexController', 
// 	'testMethod', 
// 	array( 'IndexService', array( 'bbb' )),
// 	array( 'TestModel', array( 'bbb' )),
// );


// 普通类 的用法
// $testModel = $container->make( 'TestModel' );
// $testModel->connection();

// dump( $testModel );

// $aa = $container->make( 'IndexController' );
// $IndexController = new IndexController( new IndexService() );