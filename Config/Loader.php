<?php
    
namespace Smirik\PropelSerializerBundle\Config;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use Symfony\Component\Finder\Finder;

class Loader
{
    
    protected $container;
    protected $config;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
	public function getBundles()
	{
        $tmp = $this->container->getParameter('propel_serializer');
        return $tmp['bundles'];
	}
    
    public function load()
    {
        $bundles = $this->getBundles();
        $finder  = new Finder();

        $configs  = array();
        
        foreach ($bundles as $bundle) {
            try {
                $dir_path = $this->container->get('kernel')->locateResource('@'.$bundle.'/Resources/config/propel-serializer/');
            } catch (\Exception $e) {
                throw new LoadException(sprintf("Unable to find propel-serializer directory"));
                continue;
            }
            
            $finder->files()->in($dir_path);
            foreach ($finder as $file) {
                $path = $file->getRealpath();
                $yaml = new Parser();
                try {
                    $config = $yaml->parse(file_get_contents($path));
                } catch (ParseException $e) {
                    new ParseException(sprintf("Unable to parse the config from bundle '%s'. \nYAML string: %s", $bundle, $e->getMessage()));
                }
                $configs = array_merge($config, $configs);
            }
        }
        return $configs;
    }
    
}