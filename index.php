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

// 绑定的时候用命名空间的绝对路径
// 测试二 三
// 抽象类绑定实现类的方式
$container->bind( 'Interfaces\ModelInterface', 'Service\IndexService' );
$container->bind( 'Interfaces\ModelInterface', 'Model\TestModel' );

// 执行类绑定抽象类和实现类的方式
// 执行类 - 数组( 抽象类, 实现类 )
$container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\IndexService' ));

// 方法中的绑定抽象类和实现类的方式
// 执行类 - 数组( 方法名称 - 数组( 抽象类, 实现类 ) )
$container->bind( 'Controller\IndexController', array( 'testMethod', array( 'Interfaces\ModelInterface', 'Service\IndexService' )));
$container->bind( 'Controller\IndexController', array( 'testMethod', array( 'asdf\asdf', 'Service\asdf' )));

// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Model\TestModel' ));

// 绑定别名的方法 - 接口不需要绑定别名 - 只有执行类和实现类需要绑定别名
$container->setAlias( 'ModelInterface', 'Interfaces\ModelInterface' );
$container->setAlias( 'IndexService', 'Service\IndexService' );

// dump( $container->getAlias() );
dump( $container->getBinds() );
// dump( $container->getAllAlias() );

// 绑定后的执行类和抽象类实现类, 调用方式和执行生成普通类的用法是一样的, 只需要输入命名空间命称或别名
// $container->make( 'IndexController' );
// 未绑定的可以在第二个参数加一个抽象类的实现类就可以
// $container->make( 'IndexController', 'IndexService' );

// 调用类中方法注入的执行方式 - 注意 千万不要忽略掉 第 3 个参数, 没有绑定就写 null ** 参数必须传数组
$container->makeWith( $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') ), 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );

// 第二种 - 调用类中方法注入的执行方式
$classInstance = $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') );
$container->makeWith( $classInstance, 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );


// 测试一
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\aa' ));
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\bb' ));


// $container->bind( 'Controller\IndexController', 'Interfaces\ModelInterface' );
// $container->bind( 'Controller\IndexController', 'Service\IndexService' );
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\IndexService' ) );
// $container->bind( 'Controller\IndexController', array( 'Interdfaces\ModelInterface', 'Model\TestModel' ) );

// $container->bind( 'ModelInterface', 'Interfaces\ModelInterface' );




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