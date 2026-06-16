<?php

namespace App\Form;

use App\Entity\Locataires;
use App\Entity\Logements;
use App\Entity\Garants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;        

class LocataireEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('mail')
            ->add('date_de_naissance', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])        
            ->add('lieu_de_naissance')
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Étudiant' => 'Etudiant',
                    'Salarié' => 'Salarie',
                    'Chômeur' => 'Chomeur',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Choisir un statut',
            ])            
            ->add('num_comptable')
            ->add('debut_bail', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])        
            ->add('montant_caution')
            ->add('loyer_TCC')
            ->add('restant_du_trop_percu')
            ->add('date_EDL_entree', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])        
            ->add('preavis_recu_le', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('debut_du_preavis', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_EDL_sortie', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_de_sortie', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('montant_solde_de_tout_compte')
            ->add('date_solde_de_tout_compte', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('mode_paiement_solde_de_tout_compte')
            ->add('banque_solde_de_tout_compte')
            ->add('cloture_contrat_visale')
            ->add('a_quitte_le_logement')

            ->add('LogementsID', EntityType::class, [
                'class' => Logements::class,
                'choice_label' => 'idAppart',
            ])

            ->add('commentaire', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'data' => $options['commentaire'] ?? null,
            ])

            ->add('loyer_montant', NumberType::class, [
                'mapped' => false,
                'data' => $options['loyer']?->getMontant()
            ])

            ->add('loyer_date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'data' => $options['loyer']?->getDateMES()
            ])

            ->add('PS_montant', NumberType::class, [
                'mapped' => false,
                'data' => $options['PS']?->getMontant()
            ])

            ->add('PS_date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'data' => $options['PS']?->getDateMES()
            ])

            ->add('charge_montant', NumberType::class, [
                'mapped' => false,
                'data' => $options['charge']?->getMontant()
            ])

            ->add('charge_date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'data' => $options['charge']?->getDateMES()
            ])

            ->add('garantsPhysiques', CollectionType::class, [
                'entry_type' => GarantsPhysiquesSousFormType::class,
                'mapped' => false,
                'allow_add' => true,
                'data' => $options['garantsPhysiques']
            ])

            ->add('garantsVisale', CollectionType::class, [
                'entry_type' => GarantsVisaleSousFormType::class,
                'mapped' => false,
                'allow_add' => true,
                'data' => $options['garantsVisale']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Locataires::class,
            'commentaire' => null,
            'loyer' => null,
            'PS' => null,
            'charge' => null,
            'garantsPhysiques' => [],
            'garantsVisale' => [],
        ]);
    }
}