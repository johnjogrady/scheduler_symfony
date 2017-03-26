<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfficeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('officeName')->add('addressLine1')->add('addressLine2')->add('addressLine3')
            ->add('eirCode')->add('landlineTelephone')->add('mobileTelephone')->add('isActive')
            ->add('countyPostcode', EntityType::class, array(
                // query choices from this entity
                'class' => 'AppBundle:County',
                'choice_label' => function ($county) {
                    return $county->getCountyName();
                }));


    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Office'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_office';
    }


}
