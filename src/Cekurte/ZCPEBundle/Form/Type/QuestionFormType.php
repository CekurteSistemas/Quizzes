<?php

namespace Cekurte\ZCPEBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Question type.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class QuestionFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['search'] === true) {

            $builder->add('questionType')->setRequired(false);
            $builder->add('category')->setRequired(false);
            $builder->add('updatedBy')->setRequired(false);
            $builder->add('createdBy')->setRequired(false);
            $builder->add('googleGroupsId')->setRequired(false);
            $builder->add('title')->setRequired(false);

        } else {

            $builder
                ->add('questionType')
                ->add('category')
                ->add('googleGroupsId')
                ->add('title')
                ->add('difficulty')
                ->add('comment', 'textarea', array(
                    'attr'  => array(
                        'class' => 'ckeditor'
                    )
                ))
            ;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'search'     => false,
            'data_class' => 'Cekurte\ZCPEBundle\Entity\Question'
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getName()
    {
        return 'cekurte_zcpebundle_questionform';
    }
}
