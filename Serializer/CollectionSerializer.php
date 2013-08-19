<?php
    
namespace Smirik\PropelSerializerBundle\Serializer;

class CollectionSerializer 
{
    
    protected $model_serializer;

    public function __construct($model_serializer)
    {
        $this->model_serializer = $model_serializer;
    }
    
    public function serialize($collection, $config, $group)
    {
        $res = array();
        foreach ($collection as $model) {
            $res[] = $this->model_serializer->serialize($model, $config, $group);
        }
        return $res;
        
    }
    
}