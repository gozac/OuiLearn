<?php

namespace Edu\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
                'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edu_messagebundle_message';
    }
}

