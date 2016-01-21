<?php

namespace Edu\FormaBundle\Form;

use Edu\QcmBundle\Entity\Questionnaire;
use Edu\QcmBundle\Form\QuestionnaireType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentQcmType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questionnaire', 'collection', array(
                'type'         => new QuestionnaireType(),
                'allow_add'    => true,
                'allow_delete' => true
            ))
            ->add('score_minimum', 'integer')
            ->add('save',      'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Edu\FormaBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edu_formabundle_contentqcm';
    }
}
