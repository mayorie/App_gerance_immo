<?php

namespace App\Form;

use App\Entity\PaiementsMensuels;
use App\Entity\Locataires;

use App\Repository\LocatairesRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementMensuelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LocatairesID', EntityType::class, [
                'class' => Locataires::class,

                'choice_label' => function (Locataires $locataire) {
                    return $locataire->getPrenom() . ' ' . $locataire->getNom();
                },

                'placeholder' => 'Choisir un locataire',

                'choice_attr' => function (Locataires $locataire) {

                    return [
                        'data-appart' => $locataire->getLogementsID()?->getIdAppart(),
                        'data-loyer' => $locataire->getLatestLoyer()?->getMontant(),
                        'data-charge' => $locataire->getLatestCharge()?->getMontant(),
                        'data-packs' => $locataire->getLatestCharge()?->getMontant(),
                        'data-caution' => $locataire->getPaiementsMensuels()->isEmpty()
                            ? $locataire->getMontantCaution()
                            : 0,
                        'data-restantm' => $locataire->getRestantDuTropPercu()//restant du M-1
                    ];
                },

                'query_builder' => function (LocatairesRepository $repo) {
                    return $repo->createQueryBuilder('l')
                        ->where('l.a_quitte_le_logement = :quitte')
                        ->setParameter('quitte', false)
                        ->orderBy('l.nom', 'ASC');
                }
            ])
            ->add('date')

            ->add('part_recue_du_locataire_date')
            ->add('part_recue_du_locataire_mode')
            ->add('part_recue_du_locataire_montant')

            ->add('part_recue_de_la_CAF_date')
            ->add('part_recue_de_la_CAF_mode')
            ->add('part_recue_de_la_CAF_montant')

            ->add('RBT_motif', null, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('RBT_date', null, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('RBT_mode', null, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('RBT_montant', null, [
                'mapped' => false,
                'required' => false,
            ])

            ->add('restant_du_trop_percu_fin_de_mois', null, [
                'attr' => [
                    'class' => 'restant-du-input',
                    'readonly' => true,
                ]
            ])
            ->add('RegulPacksServices')
            ->add('RegulProvisionsPourCharges')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaiementsMensuels::class,
        ]);
    }
}
