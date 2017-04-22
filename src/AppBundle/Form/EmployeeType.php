<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName',
            null, array(
                'label' => 'First Name',
                'attr' => array('style' => 'width: 200px')))
            ->add('lastName')->add('staffNumber')
            ->add('addressLine1')->add('addressLine2')->add('addressLine3')
            ->add('countyPostcode')
            ->add('eirCode')
            ->add('landlineTelephone')->add('mobileTelephone')->add('isActive')->add('managingOffice')
            ->add('startDate', DateTimeType::class, array('widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd HH:mm', 'required' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']))
            ->add('finishDate', DateTimeType::class, array('widget' => 'single_text', 'required' => false,
                'date_format' => 'yyyy-MM-dd HH:mm',
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employee'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employee';
    }


}
