<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RosterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('serviceUserId', EntityType::class, array('class' => 'AppBundle:ServiceUser',
            'data' => $options['serviceUser']
        ))->add('rosterStartTime', DateTimeType::class, array('date_widget' => "single_text", 'time_widget' => "single_text"))
            ->add('rosterEndTime', DateTimeType::class, array('date_widget' => "single_text", 'time_widget' => "single_text"))
            ->add('rosterStatus')
            ->add('numberResourcesNeeded', ChoiceType::class, array(
                'choices' => array(
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3'),
                'required' => true,
                'placeholder' => 'Choose How Many Resources Are Needed',
                'empty_data' => null
            ))
            ->add('customerId');

    }

//

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Roster',
            'serviceUser' => Null

        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_roster';
    }


}
