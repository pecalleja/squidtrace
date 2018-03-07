<?php

namespace App\Form\Type\SizeType\Type;

use App\Form\Type\SizeType\Data\Size;
use App\Form\Type\SizeType\DataTransformer\SizeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SizeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $this->getChoices($options['use_binary']);
        $builder
            ->add('value', NumberType::class, [
                'required' => $options['required'],
                'label'    => false,

            ])
            ->add('unit', ChoiceType::class, [
                'required' => true,
                'choices'  => $choices,
                'label'    => false,
            ])
            ->addModelTransformer(new SizeTransformer($options['use_binary']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Size::class,
            'use_binary' => true,
        ]);
        $resolver->addAllowedTypes('use_binary', 'bool');
    }

    private function getChoices($use_binary)
    {
        if (true === $use_binary) {
            return [
                'B'  => Size::UNIT_B,
                'KB' => Size::UNIT_KB,
                'MB' => Size::UNIT_MB,
                'GB' => Size::UNIT_GB,
                'TB' => Size::UNIT_TB,
                'PB' => Size::UNIT_PB,
                'EB' => Size::UNIT_EB,
            ];
        }

        return [
            'B'  => Size::UNIT_B,
            'KB' => Size::UNIT_KB,
            'MB' => Size::UNIT_MB,
            'GB' => Size::UNIT_GB,
            'TB' => Size::UNIT_TB,
            'PB' => Size::UNIT_PB,
            'EB' => Size::UNIT_EB,
        ];
    }
}
