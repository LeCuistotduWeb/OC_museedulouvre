<?php

namespace OC\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisite', DateType::class,
            [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker','placeholder' => 'Date de votre visite'],
                'format' => 'yyyy-MM-dd',
            ])

            ->add('tickets', CollectionType::class ,[
                'entry_type'   => TicketType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label'=> false,
                'by_reference' => false,
                'prototype' => true,
            ])

            ->add('emailSend', RepeatedType::class,[
                'type' => EmailType::class,
                'invalid_message' => 'Les emails ne sont pas identique.',
                'first_options'  => array('label' => 'Email de réception des billets'),
                'second_options' => array('label' => 'Confirmer l\'adress email de reception '),
            ]);

//            ->add('save', SubmitType::class, array('label' => 'Suivant'));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\LouvreBundle\Entity\CommandeInterface'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_louvrebundle_commande';
    }


}
