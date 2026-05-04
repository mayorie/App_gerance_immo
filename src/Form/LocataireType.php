<?php

namespace App\Form;

use App\Entity\Commentaires;
use App\Entity\Locataires;
use App\Entity\Logements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\GarantsPhysiquesSousFormType;
use App\Form\GarantsVisaleSousFormType;

class LocataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('mail')
            ->add('date_de_naissance')
            ->add('lieu_de_naissance')
            ->add('statut')
            ->add('num_comptable')
            ->add('debut_bail')
            ->add('montant_caution')
            ->add('date_EDL_entree')
            ->add('num_comptable')
            ->add('LogementsID', EntityType::class, [
                'class' => Logements::class,
                'choice_label' => 'idAppart',
            ])

            // LOYER HC
            ->add('loyer_montant', NumberType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('loyer_date', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text'
            ])

            // CHARGE
            ->add('charge_montant', NumberType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('charge_date', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text'
            ])

            // Packs Services (PS)
            ->add('PS_montant', NumberType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('PS_date', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text'
            ])

            ->add('garantsPhysiques', CollectionType::class, [
                'entry_type' => GarantsPhysiquesSousFormType::class,
                'mapped' => false, // 🔥 important pour commencer simple
                'allow_add' => true,
                'prototype' => true,
            ])

            ->add('garantsVisale', CollectionType::class, [
                'entry_type' => GarantsVisaleSousFormType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
            ])
            
            ->add('commentaire', TextareaType::class, [
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Locataires::class,
        ]);
    }
}
