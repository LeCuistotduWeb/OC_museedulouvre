<?php

namespace OC\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
                'by_reference' => false,
                'prototype' => true,
            ])
            
            ->add('emailSend', EmailType::class,[
                'attr' => ['class' => 'datepicker','placeholder' => 'Email de réception de vos billet.'],
            ])
            
            ->add('save', SubmitType::class, array('label' => 'Suivant'));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\LouvreBundle\Entity\Commande'
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
