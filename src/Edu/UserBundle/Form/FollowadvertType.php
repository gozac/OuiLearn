<?php

namespace Edu\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class FollowadvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comprehension', 'choice', array(
                'choices' => array(
                    1   => 'Tout à fait',
                    0 => 'Un peu',
                    -1   => 'Pas d\'accord',
                ),
                'expanded' => true,
            ))
            ->add('resolution', 'choice', array(
                'choices' => array(
                    1   => 'Tout à fait',
                    0 => 'Un peu',
                    -1   => 'Pas d\'accord',
                ),
                'expanded' => true,
            ))
            ->add('logique', 'choice', array(
                'choices' => array(
                    1   => 'Tout à fait',
                    0 => 'Un peu',
                    -1   => 'Pas d\'accord',
                ),
                'expanded' => true,
            ))
            ->add('ludique', 'choice', array(
                'choices' => array(
                    1   => 'Tout à fait',
                    0 => 'Un peu',
                    -1   => 'Pas d\'accord',
                ),
                'expanded' => true,
            ))
            ->add('coaching', 'checkbox', array('required' => false))
            ->add('remarque', 'text', array('required' => false))
            ->add('save',      'submit')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edu_userbundle_followadvert';
    }
}