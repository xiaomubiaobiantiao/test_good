<?php

namespace Container;
use ReflectionClass;
use ReflectionException;

class Container
{


	private $bindings = array();
	private $associated = '';
	private $alias = array();

	public function bind( $abstract, $concrete = null ) {

		if ( is_array( $concrete )) {
			if ( count( $concrete ) == count( $concrete, 1 )) {
				$this->bindings[$abstract][$concrete[0]] = $concrete[1];
			} else {
				// $cc = array( $concrete[1][0], $concrete[1][1] );
				$ccc =$this->bindings[$abstract][$concrete[0]][$concrete[1][0]] = $concrete[1][1];
			}
		} else {
			// 留着以后做单例的时候用 - 目前未开发 - 单例为 true
			is_null( $concrete )
                ? $this->bindings[$abstract] = true
                : $this->bindings[$abstract][$concrete] = $concrete;
		}

	}

	/**
	 * [make 建立实例]
	 * @param  [type] $concrete   [实现类]
	 * @param  [type] $associated [绑定实现类接口的类]
	 * @param  [type] $params     [实现类构造方法需要的参数]
	 * @return [type]             [实现类的实例和相关联的所有实例]
	 */
	public function make( $concrete, $associated = null, $params = null, $bool = false ) {

		// 如果第二个参数是数组 并且第三个参数不为 true 将最后两个参数值互换
		// 相当于 没有输入绑定抽象类的实体类 直接输入了数组参数
		if ( is_array( $associated ) && false == is_bool( $params ) )
            list( $associated, $params ) = array( $params, $associated );

        // 如果第二个参数是数组 并且第三个参数为 true 将三个参数值互换
		// 相当于 没有输入绑定抽象类的实体类 直接输入了数组参数 并且指定了要传容器类到参数中
        if ( is_array( $associated ) && is_bool( $params ) )
            list( $params, $bool, $associated ) = array( $associated, $params, null );  

		//获取别名数组
		$alias = $this->getAllAlias();
		// dump( $alias );
		//分析 实现类全名 和 绑定的类的全名 别名并赋值全名
		// switch ( func_num_args() || is_null( $associated ) {
		switch ( is_null( $associated ) {
			case 1:
				if ( $alias[$concrete] )
					$concrete = $alias[$concrete];
				break;
			default:
				if ( $alias[$concrete] && $alias[$associated] )
					list( $concrete, $associated ) = array( $alias[$concrete], $alias[$associated]);
				break;

		}
		// dump( $concrete );
		// dump( $associated );
		// dump( $params );
		// 返回实例
		return $this->getInstance( $concrete, $associated, $params );

	}

	// 从普通方法中获取参数并反射
	// public function makeWith( $className, $method, $methodAssociated = null, $methodParams = null, $classAssociated = null, $constructParams = null ) {

	public function makeWith( $classInstance, $method, $methodAssociated = null, $methodParams = null, $bool = false ) {
		
		// 如果第二个参数是数组 并且第三个参数不为 true 将最后两个参数值互换
		// 相当于 没有输入绑定抽象类的实体类 直接输入了数组参数
		if ( is_array( $associated ) && false == is_bool( $params ) )
            list( $associated, $params ) = array( $params, $associated );

        // 如果第二个参数是数组 并且第三个参数为 true 将三个参数值互换
		// 相当于 没有输入绑定抽象类的实体类 直接输入了数组参数 并且指定了要传容器类到参数中
        if ( is_array( $associated ) && is_bool( $params ) )
            list( $params, $bool, $associated ) = array( $associated, $params, null );  

        // 获取别名列表
		$alias = $this->getAllAlias();

		if ( $alias[$methodAssociated] )
			$methodAssociated = $alias[$methodAssociated];

		$className = $this->reflecterClass( $classInstance );
	
		$reflecter = new ReflectionClass( $className );

		$reflecterMethod = $reflecter->getMethod( $method );
		
		$parameters = $reflecterMethod->getParameters();

		foreach ( $parameters as $param ) {
			$class = $param->getClass();
			if ( $class ) {
				if ( interface_exists( $class->name ) ) {

					if ( is_null( $methodAssociated ) )
						$methodAssociated = $this->searchBindInterface( $className, $class->name, $method );
					//如果接口没绑定 提示绑定接口
					if ( empty( $this->bindings[$class->name][$methodAssociated] ))
                        die( 'not found class, bind abstract or interface please !' );

					$instance[] = $this->make( $this->bindings[$class->name][$methodAssociated] );
				} else {
					$instance[] = $this->getInstance( $class->name );
				}
			}
		}

		// 如果指定需要传容器到参数中 执行下面的判断
		if ( true === array_pop( array_filter( func_get_args())))
            array_unshift( $methodParams, $this );

        // 如果没有绑定抽象类 就返回给正常的参数
		empty( $instance )
            ? $instance[] = $methodParams
            : array_push( $instance, $methodParams );

		return call_user_func_array( array( $classInstance, $method ), $instance );

	}

	// 从构造方法中获取参数并反射
	public function getInstance( $className, $associated = null, $params = null ) {

		$reflecter = new ReflectionClass( $className );
		
		$constructor = $reflecter->getConstructor();
		
		$instance = array();

		if ( false == $constructor )
			return $reflecter->newInstanceArgs( $instance );

		$parameters = $constructor->getParameters();

		// 测试三 - 目前用测试三
		foreach ( $parameters as $param ) {
			//只能查看参数的变量名
			// dump( $param );
			//可以查看参数的绑定名称 比如接口或类 的 use 路径
			//如果接口或类 的 use 使用了 as 别名
			//参数也要改成别名
			// echo  $param ;
			$class = $param->getClass();

			if ( $class ) {

				// 如果是接口的时候执行
				if ( interface_exists( $class->name ) ) {
					// 如果没有传递实现类的参数 搜索是否有绑定了该接口的唯一参数
					if ( is_null( $associated ) )
						$associated = $this->searchBindInterface( $className, $class->name );
					
					//如果接口没绑定 提示绑定接口
					if ( empty( $this->bindings[$class->name][$associated] ))
                        die( 'not found class, bind abstract or interface please !' );
					
					$instance[] = $this->make( $this->bindings[$class->name][$associated] );
				} else {
					$instance[] = $this->getInstance( $class->name );
				}
			//暂时未用到 - 留到以后详细写参数的传值的时候再用
			} else {
				$classParam[] = $param->name;
			}

		}		

		// 测试二
		// foreach ( $parameters as $param ) {
		// 	$paramName = $param->getClass()->name;
		// 	if ( interface_exists( $paramName ) && $this->bindings[$className][$paramName] ) {
		// 		$instance[] = $this->getInstance( $this->bindings[$paramName][$associated] );
		// 	} else {
		// 		$class = $param->getClass();
		// 		if ( $class )
		// 			$instance[] = $this->getInstance( $class->name );
		// 	}
		// }


		// 测试一
		// foreach ( $parameters as $param ) {

			// $alias = $this->bindAlias();
			// if ( $alias[$param->name] )
			// 	$paramName = $alias[$param->name];

			// if ( interface_exists( $paramName ) ) {

			// 	if ( $this->bindings[$className][$paramName] )
			// 		$paramName = $this->bindings[$paramName][$associated];

			// 	$instance[] = $this->getInstance( $paramName );
			// 	dump( $instance );

			// } else {
			// 	$class = $param->getClass();
			// 	if ( $class )
			// 		$instance[] = $this->getInstance( $class->name );
			// }
		// }

		//如果指定需要传容器到参数中 执行下面的判断
		if ( true === array_pop( array_filter( func_get_args())))
            array_unshift( $params, $this );

		//将参数 追加进 需要创建的 实体类的参数 里面
		array_push( $instance, $params );
		return $reflecter->newInstanceArgs( $instance );
		// return $reflecter->newInstanceArgs( array_push( $instance, $params ) );

		// $this->dump( $reflecter );
		// $this->dump( $constructor );
		// $this->dump( $parameters );
	}

	// 搜索类的构造函数或方法 参数 的抽象类是否绑定实现类
	public function searchBindInterface( $className, $abstract, $methodName = null ) {
		
		// 判断是类中的方法绑定 还是 类绑定
		if ( is_null( $methodName ) ) {
			// 如果绑定了对应接口的实现类 直接返回实现类 命名空间
			if ( $this->bindings[$className][$abstract] )
				return $this->bindings[$className][$abstract];
		} else {
			// 如果绑定了对应接口的实现类时 直接返回实现类 命名空间
			if ( $this->bindings[$className][$methodName][$abstract] )
				return $this->bindings[$className][$methodName][$abstract];
		}

		// 如果未绑定对应接口的实现类 并且接口实现类不存在 或者 大于 1 个以上的时候报错
		if ( count( $this->bindings[$abstract] ) != 1 ) {
			die( 'getInstance: $this->bindings( '.$abstract.' ) only one Interface OR to 2 parameter bind class Concrete   please !' );
		} 

		// 如果未绑定对应接口的实现类 但是对应接口有且仅有一个实现类时 返回这个实现类
		return current( $this->bindings[$abstract] );


	}

	// 绑定别名
	public function bindAlias() {
		return array(
			'IndexController' => 'Controller\IndexController',
			'ModelInterface' => 'Interfaces\ModelInterface',
			'TestModel' => 'Model\TestModel',
		);
	}

	// 动态绑定别名
	public function setAlias( $alias, $fullName ) {
		$this->alias[$alias] = $fullName;
	}

	// 获取动态别名
	public function getAlias() {
		return $this->alias;
	}

	// 获取全部别名配置
	public function getAllAlias() {
		// $aa = array(
		// 	$this->getAlias(),
		// 	$this->bindAlias(),
		// );
		// dump( $aa );
		// echo 123;
		return array_merge( $this->getAlias(), $this->bindAlias() );
		// return $this->getAlias() + $this->bindAlias();
	}

	// 获取绑定参数
	public function getBinds() {
		return $this->bindings;
	}

	// 反射类的名称
	private function reflecterClass( $class ) {
		$reflecterClass = new ReflectionClass( $class );
		return $reflecterClass->name;
	}




}