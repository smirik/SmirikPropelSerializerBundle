<?php

namespace Smirik\PropelSerializerBundle\Serializer;

class Serializer
{
    
    protected $config;
    protected $model_serializer;
    protected $collection_serializer;
    protected $pager_serializer;
    protected $form_serializer;

    public function __construct($loader, $model_serializer, $collection_serializer, $pager_serializer, $form_serializer)
    {
        $this->setConfig($loader->load());
        $this->model_serializer      = $model_serializer;
        $this->collection_serializer = $collection_serializer;
        $this->pager_serializer      = $pager_serializer;
        $this->form_serializer       = $form_serializer;
    }
    
    public function serialize($data, $group = 'default')
    {
        $class = get_class($data);
        
        switch ($class) {
            case 'PropelObjectCollection':
                return $this->collection_serializer->serialize($data, $this->config, $group);
                break;
            
            case 'PropelModelPager':
                return $this->pager_serializer->serialize($data, $this->config, $group);
                break;
            
            case 'Symfony\\Component\\Form\\Form':
                return $this->form_serializer->serialize($data);
                break;
            
            default:
                return $this->model_serializer->serialize($data, $this->config, $group);
                break;
        }
    }
    
	public function setConfig($config)
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}
    
}