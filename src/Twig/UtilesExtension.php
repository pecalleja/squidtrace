<?php
namespace App\Twig;

use Symfony\Component\Validator\Constraints\DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UtilesExtension extends AbstractExtension
{

    public function getFilters()
    {
        return array(
            new TwigFilter('slug', array($this,'SlugTransformFilter')),
            new TwigFilter('mes_nombre', array($this,'getMesNombreFilter')),
            new TwigFilter('day_nombre', array($this,'getDiaSemanaNombreFilter')),
            new TwigFilter('dias_key', array($this,'getDiasKeysFilter')),
            new TwigFilter('dias_value', array($this,'getDiasValueFilter')),
            new TwigFilter('horas_key', array($this,'getHorasKeysFilter')),
            new TwigFilter('horas_value', array($this,'getHorasValueFilter'))
        );
    }

    public function SlugTransformFilter($value)
    {
		$cadena = $value;
		$separador = "-";
		$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        return $slug;
    }

    public function getMesNombreFilter($value)
    {
        $meses = array(
        1 => 'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
        );
        if($value >= 1 && $value<=12){
            return $meses[(int)$value];
        }else{
            return "ErrorMes";
        }
    }

    public function getDiaSemanaNombreFilter($value)
    {
        $semana = array(
        0 => 'Dom',
        1 => 'Lun',
        2 => 'Mar',
        3 => 'Mie',
        4 => 'Jue',
        5 => 'Vie',
        6 => 'Sab',
        );
        if($value >= 0 && $value<=6){
            return $semana[(int)$value];
        }else{
            return "ErrorSemana";
        }
    }

    public function getDiasKeysFilter($value,$i=false)
    {
        $dias = array();
        foreach ($value as $item){
            $dias[$item['fecha']->format("j")]= $item['fecha']->format("j");
        }
        return $dias;
    }

    public function getDiasValueFilter($value,$i=false)
    {
        $dias = array();
        foreach ($value as $item){
            $dias[$item['fecha']->format("j")]= $item['carga'];
        }
        if($i === false)
            return $dias;
        else{
            return doubleval($dias[$i]);
        }

    }

    public function getHorasKeysFilter($value){
        $horas=array();
        foreach ($value as $item){
            $horas[$item['hora']]=$item['hora'];
        }
        return $horas;
    }

    public function getHorasValueFilter($value,$i=false){
        $horas = array();
        foreach ($value as $item){
            $horas[$item['hora']] = $item['carga'];
        }
        if($i === false)
            return $horas;
        else{
            return doubleval($horas[$i]);
        }
    }
}