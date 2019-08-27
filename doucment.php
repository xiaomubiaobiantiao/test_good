<?php


//bind的结果应该是...
array(
	'ModelInterface' => array( 
							'IndexService',
							'aaaa',
							'bbbb'
						),
	'IndexController' => array( 'ModelInterface', 'IndexService' ),
						 array( 'ModelInterface', 'aaaa' ),
						 array( 'ModelInterface', 'bbbb' )

);

//执行的流程应该是...
$this->make( 'IndexController','IndexService', $params );

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

//接口多个实现类绑定 - 产生反射
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