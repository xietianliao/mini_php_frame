<?php
namespace core;

class Psr4AutoLoad
{
    //命名空间映射
    protected $maps = [];

    public function __construct()
    {
        spl_autoload_register([$this,'autoload']);
    }

    public function autoload($className)
    {
        /*
			当自动加载为首页时
            $className = controller\IndexController
            $namespace = controller
            $realClass = IndexController
        */
        //完整的类名由空间名和类名组成
        //得到命名空间名，根据命名空间名得到其目录路径
        $pos = strrpos($className, '\\');
        $namespace = substr($className, 0 , $pos);
		
		//校验数据是否正常
		if($namespace && $pos)
		{
			//得到类名
			$realClass = substr($className,$pos + 1);
			//找到文件并且包含
			$this->mapLoad($namespace,$realClass);
		}
    }

    public function mapLoad($namespace,$realClass)
    {
        //判断是否映射过
        if(array_key_exists($namespace, $this->maps))
		{
            $namespace = $this->maps[$namespace];
		}
		
        //处理路径
        $namespace = rtrim(str_replace('\\/', '/', $namespace), '/') . '/';
        //拼接文件全路径
        $filePath = $namespace.$realClass.'.php';

        if(file_exists($filePath) && !class_exists($realClass))
		{
            include $filePath;
		}
		else
		{
			die('类不存在 :)');
		}
    }

    //命名空间 路径  将命名空间和路径保存到映射数组中
    public function addMaps($namespace, $path)
    {
        if(array_key_exists($namespace, $this->maps))
		{
            die('此命名空间已经映射 :)');
		}
		
        //将命名空间和路径以键值对形式存放到数组中
        $this->maps[$namespace] = $path;
    }
}