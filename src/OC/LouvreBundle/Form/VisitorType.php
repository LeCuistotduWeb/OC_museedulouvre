<?php

namespace OC\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class,[
                'label'  => false,
                'attr' => array(
                    'placeholder' => 'Nom',
                ),
            ])
            ->add('name', TextType::class,[
                'label'  => false,
                'attr' => array(
                    'placeholder' => 'Prénom',
                )
            ])
            ->add('dateBirthday', BirthdayType::class, [
                'label' => 'Date de naissance : ',
                'format' => 'dd-MM-yyyy',
                'years' => range(date("Y")-110, date("Y")),
                ])
            ->add('country', CountryType::class, [
                'label'  => 'Votre pays : ',
                'preferred_choices' => array('FR'),
            ])
            ->add('reduction', CheckboxType::class, [
                'label' => 'Je bénéficie d\'un tarif réduit. (étudiant, employé du musée, d’un service du Ministère de la Culture, militaire…).',
                'required' => false,
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\LouvreBundle\Entity\Visitor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_louvrebundle_visitor';
    }


}
