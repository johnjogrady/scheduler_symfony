<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeAbsenceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('StartTime', DateTimeType::class, array('date_widget' => "single_text", 'time_widget' => "single_text"))
            ->add('EndTime', DateTimeType::class, array('date_widget' => "single_text", 'time_widget' => "single_text"))
            ->add('absenceReason')->
            add('employeeId', EntityType::class, array('class' => 'AppBundle:Employee',
                'data' => $options['employee']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EmployeeAbsence',

            'employee' => Null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employeeabsence';
    }


}
