<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UnidadMedidaExtension extends AbstractExtension
{

    public function getFilters()
    {
        return array(
            new TwigFilter('cant_bit', array($this,'CantBitFilter')),
        	new TwigFilter('unidad_bit', array($this,'UnidadBitFilter')),
        	new TwigFilter('porciento', array($this,'PorcientoFilter')),
        	new TwigFilter('label', array($this,'LabelFilter')),
			new TwigFilter('megas', array($this,'MegasFilter'))
        );
    }

	public function MegasFilter($value)
	{
		return doubleval(number_format($value/1048576,2));//devuelvo el valor en MB
	}

    public function CantBitFilter($value)
    {
    	if($value >= 0 && $value <= 1024)
			return number_format($value,2);//devuelvo el valor en B
    	elseif ($value > 1024 && $value <=1048576)
    		return number_format($value/1024,2);//devuelvo el valor en KB
    	elseif($value > 1048576 && $value <= 1073741824)
    		return number_format($value/1048576,2);//devuelvo el valor en MB
    	elseif($value > 1073741824)
    		return number_format($value/1073741824,2);//devuelvo el valor en GB
    }
    
    public function UnidadBitFilter($value)
    {
    	if($value >= 0 && $value <= 1024)
    		return "B";//devuelvo el valor en B
    	elseif ($value > 1024 && $value <=1048576)
    		return "KB";//devuelvo el valor en KB
    	elseif($value > 1048576 && $value <= 1073741824)
    		return "MB";//devuelvo el valor en MB
    	elseif($value > 1073741824)
    		return "GB";//devuelvo el valor en GB
    }

    public function LabelFilter($value)
    {
    	if($value >= 0 && $value <= 10)
    		return "info";
    	elseif ($value > 10 && $value <=89)
    		return "success";
    	elseif($value > 89 && $value < 98)
    		return "warning";
    	elseif($value >= 98)
    		return "danger";
    }
    
    public function PorcientoFilter($value)
    {
    	return number_format($value,2);
    }
}