<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeUnavailabilityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('unavailabilityReason')->
        add('employeeId', EntityType::class, array('class' => 'AppBundle:Employee',
            'data' => $options['employee']))
            ->add('dayOfWeek', ChoiceType::class, array(
                'choices' => array(
                    'Monday' => 1,
                    'Tuesday' => 2,
                    'Wednesday' => 3,
                    'Thursday' => 4,
                    'Friday' => 5,
                    'Saturday' => 6,
                    'Sunday' => 0,
                ),
                'choice_attr' => function ($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'dayOfWeek_' . strtolower($key)];
                },
            ))
            ->add('unavailabilityReason')->add('startTime')->add('endTime');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EmployeeUnavailability',
            'employee' => Null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_unavailabilityreason';
    }


}
