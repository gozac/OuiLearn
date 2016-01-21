<?php

namespace Edu\QcmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReponseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', 'choice', array(
                'choices' => array(
                    1   => '',
                    2 => 'Un peu',
                    3 => 'Pas d\'accord',
                    4 => 'Pas d\'accord'), 'choices_as_values' => true,
                'choice_label' => function($value, $key, $index) {
                    /** @var Category $category */
                    return strtoupper($category->getName());
                },
                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Edu\QcmBundle\Entity\Reponse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edu_qcmbundle_Reponse';
    }
}
