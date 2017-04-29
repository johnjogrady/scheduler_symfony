<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RosterEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $timezone = new \DateTimeZone("Europe/Dublin");

        if (isset ($_REQUEST['rosterDate'])) {
            $builder->add('serviceUserId')
                ->add('rosterStartTime', DateTimeType::class,
                    array('date_widget' => "single_text",
                        'time_widget' => "single_text",
                        'data' => new \DateTime($_REQUEST['rosterDate'], $timezone)))
                ->add('rosterEndTime', DateTimeType::class, array('date_widget' => "single_text",
                    'time_widget' => "single_text",
                    'data' => new \DateTime($_REQUEST['rosterDate'], $timezone)))
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
        } else {
            $builder->add('serviceUserId')
                ->add('rosterStartTime', DateTimeType::class,
                    array('date_widget' => "single_text",
                        'time_widget' => "single_text",
                    ))
                ->add('rosterEndTime', DateTimeType::class, array('date_widget' => "single_text",
                    'time_widget' => "single_text",
                ))
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
                ->add('customerId')->add('rosterStatus');
        }
    }
//

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Roster',
            'serviceUser' => Null,
            'rosterDate' => Null

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
