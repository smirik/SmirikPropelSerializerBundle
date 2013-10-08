<?php

namespace Smirik\PropelSerializerBundle\Serializer;
    
class ModelSerializer
{
    
    public function serialize($model, $configs, $group)
    {
        $config = $this->getConfig($model, $configs, $group);
        $value = $this->getValue($model, $config);
        return $value;
    }
	
	public function getValue($model, $config) {
	    $res = array();
	    foreach ($config as $value) {
		$this->processConfigValue($value, $model, $res);
	    }
	    return $res;
	}
	
	private function processConfigValue($value, $model, &$res)
	{
		if (is_array($value) && isset(reset($value)['getter'])) {
	          $invoker = reset($value)['getter'];
	          $fieldName = key($value);
		} else {
	          $invoker = 'get'.$value;
	          $fieldName = $value;
	      }
		$invoker = \Symfony\Component\DependencyInjection\Container::camelize($invoker);
 
		$res[$fieldName] = $model->{$invoker}();
	}
    
    public function getConfig($model, $configs, $group)
    {
        $class = str_replace("\\", "\\\\", get_class($model));
        if (!array_key_exists($class, $configs)) {
            throw new RuleNotFoundException('Rule for model '.$class.' not found.');
        }
        $config = $configs[$class];
        
        if (isset($config[$group])) {
            return $config[$group];
        } else {
            if (isset($config['default'])) {
                return $config['default'];
            }
            throw new RuleNotFoundException("Rule for group '".$group."' is not presented. Default group is not defined.");
        }
        
    }
    
}