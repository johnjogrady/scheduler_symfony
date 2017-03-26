<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
            ->add('lastName')
            ->add('addressLine1')->add('addressLine2')->add('addressLine3')->add('eirCode')
            ->add('landlineTelephone')->add('mobileTelephone')->add('isActive')
            ->add('startDate', DateTimeType::class, array('widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd HH:mm',
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']))
            ->add('finishDate', DateTimeType::class, array('widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd HH:mm',
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']))
            ->add('countyPostcode')->add('managingOffice');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ServiceUser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_serviceuser';
    }


}
