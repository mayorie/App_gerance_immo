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
            ->add('debut_bail')
            ->add('montant_caution')
            ->add('loyer_TCC')
            ->add('restant_du_trop_percu')
            ->add('date_EDL_entree')
            ->add('preavis_recu_le')
            ->add('debut_du_preavis')
            ->add('date_EDL_sortie')
            ->add('montant_solde_de_tout_compte')
            ->add('date_solde_de_tout_compte')
            ->add('mode_paiement_solde_de_tout_compte')
            ->add('banque_solde_de_tout_compte')
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
