<?php

// 绑定的时候用命名空间的绝对路径

// 测试二 三
// 抽象类绑定实现类的方式
$container->bind( 'Interfaces\ModelInterface', 'Service\IndexService' );
$container->bind( 'Interfaces\ModelInterface', 'Model\TestModel' );

// 执行类绑定抽象类和实现类的方式 - 适和于抽象类有多个实现类 而我们只想让执行类默认反射指定的实现类
// 执行类 - 数组( 抽象类, 实现类 )
$container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Service\IndexService' ));
// $container->bind( 'Controller\IndexController', array( 'Interfaces\ModelInterface', 'Model\TestModel' ));

// 方法中绑定抽象类和实现类的方式 - 适和于抽象类有多个实现类 而我们只想让执行类默认反射指定的实现类
// 执行类 - 数组( 方法名称 - 数组( 抽象类, 实现类 ) )
$container->bind( 'Controller\IndexController', array( 'testMethod', array( 'Interfaces\ModelInterface', 'Service\IndexService' )));
// $container->bind( 'Controller\IndexController', array( 'testMethod', array( 'asdf\asdf', 'Service\asdf' )));

// 绑定别名的方法 - 接口不需要绑定别名 - 只有执行类和实现类需要绑定别名
$container->setAlias( 'ModelInterface', 'Interfaces\ModelInterface' );
$container->setAlias( 'IndexService', 'Service\IndexService' );

// 查看已绑定的别名
dump( $container->getAlias() );

// 查看静态绑定的别名
dump( $container->getBinds() );

// 查看已绑定的所有别名
dump( $container->getAllAlias() );

// 绑定后的执行类和抽象类实现类,调用方式和执行生成普通类的用法是一样的,只需要输入命名空间命称或别名
$container->make( 'IndexController' );
// 
// 未绑定的可以在第二个参数加一个抽象类的实现类就可以
$container->make( 'IndexController', 'IndexService' );

// 调用类中方法注入的执行方式 - 注意 千万不要忽略掉 第 3 个参数, 没有绑定就写 null ** 参数必须传数组
$container->makeWith( $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') ), 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );

// 第二种 - 调用类中方法注入的执行方式
$classInstance = $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') );
$container->makeWith( $classInstance, 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );

//bind的结果应该是...
Array
(
    '[Interfaces\ModelInterface] => Array
        (
            [Service\IndexService] => Service\IndexService
            [Model\TestModel] => Model\TestModel
        )

    [Controller\IndexController] => Array
        (
            [Interfaces\ModelInterface] => Service\IndexService
        )'

)

// 执行的流程应该是...
$this->make( 'IndexController', $params );	// 绑定了接口和实现类的方式 || 普通执行方式
// 或者
$this->make( 'IndexController','IndexService', $params );	//未绑定接口和实现类 指定实现类方式

// 调用类中方法注入的执行方式 - 注意 千万不要忽略掉 第 3 个参数, 没有绑定就写 null ** 参数必须传数组
$container->makeWith( $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') ), 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );

// 第二种 - 调用类中方法注入的执行方式
$classInstance = $container->make( 'IndexController', 'TestModel', array('99875','6546ds4') );
$container->makeWith( $classInstance, 'testMethod', null, array( 'asdf', 'sda;flkjsa' ) );




$this->getInstance( $abstract, $concrete );

	//提取参数 getParamters
	__construct( ModelInterface );

		//判断路径
		ModelInterface => Interfaces\ModelInterface

	//判断抽象类
	if ( is_abstract( ModelInterface ))

		//获取传进来的类的bind信息
		$abstract = IndexController

		//搜索绑定信息里是否有 ModelInterface 抽象类, 如果有搜索 $concrete 是否存在 如 IndexService
		
		返回回来 传进make或getInstance();



//普通类不需要绑定 直接执行
$this->make( 'IndexController', $params );

//------------------------------------------------------------------------//

//接口多个实现类绑定 - 用来反射
$this->bind( 'Interface\ModelInterface', 'Service\IndexService' );
$this->bind( 'Interface\ModelInterface', 'aaaa' );
$this->bind( 'Interface\ModelInterface', 'bbbb' );
$this->bind( 'Controller\IndexController', 'Interface\ModelInterface', 'IndexService' );

$this->make( 'IndexController', 'IndexService', $params );

//------------------------------------------------------------------------//

//接口单个实现类绑定
$this->bind( 'Interface\ModelInterface', 'Service\IndexService' );

//不需要指定接口的实现类 因为只有一个
$this->bind( 'Controller\IndexController', 'Interface\ModelInterface' );

//执行
$this->make( 'IndexController', $params );

//------------------------------------------------------------------------//