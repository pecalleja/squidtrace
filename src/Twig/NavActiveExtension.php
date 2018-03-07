<?php

namespace App\Twig;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NavActiveExtension extends AbstractExtension
{
	protected $requestStack;
	
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('nav_active', array($this,'NavActiveFilter'))
        );
    }

    public function NavActiveFilter($value)
    {
        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');
		if(!strcmp($route, $value)){
			return "active";
		}else{
			return "";
		}
    }
}