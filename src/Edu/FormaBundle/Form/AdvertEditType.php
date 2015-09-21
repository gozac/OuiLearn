<?php

namespace Edu\FormaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->remove('date');
        $builder->remove('content');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edu_formabundle_advert_edit';
    }
    public function getParent()
    {
        return new AdvertType();
    }
}
