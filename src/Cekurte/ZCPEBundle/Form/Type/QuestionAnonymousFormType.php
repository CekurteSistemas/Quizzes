<?php

namespace Cekurte\ZCPEBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Question anonymous type.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class QuestionAnonymousFormType extends QuestionFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if ($options['search'] !== true) {

            $builder
                ->remove('title')
                ->remove('questionType')
                ->add('title', 'textarea')
                ->add('questionType', null, array(
                    'empty_value'   => ''
                ))
                ->add('choose', 'choice', array(
                    'label'         => 'Number of correct answers',
                    'mapped'        => false,
                    'choices'       => array(1, 2, 3, 4, 5),
                    'empty_value'   => '',
                ))
            ;

        }
    }
} 