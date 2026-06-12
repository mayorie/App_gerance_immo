<?php

namespace App\Form;

use App\Entity\PaiementsMensuels;
use App\Entity\Locataires;

use App\Repository\LocatairesRepository;
use App\Repository\RBTBailleurRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementMensuelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LocatairesID', EntityType::class, [
                'class' => Locataires::class,
                

                'choice_label' => function (Locataires $locataire) {
                    return $locataire->getNom() . ' ' . $locataire->getPrenom();
                },

                'placeholder' => 'Choisir un locataire',

                'choice_attr' => function (Locataires $locataire) {

                    $datesPaiements = [];

                    foreach ($locataire->getPaiementsMensuels() as $paiement) {

                        if ($paiement->getDate()) {
                            $datesPaiements[] = $paiement->getDate()->format('Y-m-d');
                        }
                    }

                    return [
                        'data-appart' => $locataire->getLogementsID()?->getIdAppart(),
                        'data-charge' => $locataire->getLatestCharge()?->getMontant(),
                        'data-packs' => $locataire->getLatestPackServices()?->getMontant(),
                        'data-caution' => $locataire->getPaiementsMensuels()->isEmpty()
                            ? $locataire->getMontantCaution()
                            : 0,
                        'data-restantm' => $locataire->getRestantDuTropPercu(),
                        'data-loyer' => $locataire->getLatestLoyer()?->getMontant(),
                        'data-bail-in' => $locataire->getDebutBail()?->format('Y-m-d'),
                        'data-bail-out' => $locataire->getDateDeSortie()?->format('Y-m-d'),

                        'data-paiements' => implode(',', $datesPaiements),
                    ];
                },
                'attr' => [
                    'class' => 'input-medium'
                ],

                'query_builder' => function (LocatairesRepository $repo) {
                    return $repo->createQueryBuilder('l')
                        ->where('l.a_quitte_le_logement = :quitte')
                        ->setParameter('quitte', false)
                        ->orderBy('l.nom', 'ASC');
                }
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input-medium paiement-date'
                ]
            ])

            ->add('part_recue_du_locataire_date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input-medium'
                ]
            ])
            ->add('part_recue_du_locataire_mode', ChoiceType::class, [
                'choices' => [
                    'VIR' => 'VIR',
                    'ESP' => 'ESP',
                    'CB' => 'CB',
                    'CHQ' => 'CHQ',
                    'Autres' => 'Autres',
                ],
                'data' => 'VIR',
                'required' => false,
                'placeholder' => false,
                'attr' => [
                    'class' => 'input-small'
                ]
            ])
            ->add('part_recue_du_locataire_montant', null, [
                'attr' => [
                    'class' => 'input-small'
                ]
            ])
            ->add('part_recue_de_la_CAF_date', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input-medium'
                ]
            ])
            ->add('part_recue_de_la_CAF_mode', ChoiceType::class, [
                'choices' => [
                    'VIR' => 'VIR',
                    'ESP' => 'ESP',
                    'CB' => 'CB',
                    'CHQ' => 'CHQ',
                    'Autres' => 'Autres',
                ],
                'data' => 'VIR',
                'required' => false,
                'placeholder' => false,
                'attr' => [
                    'class' => 'input-small'
                ]
            ])
            ->add('part_recue_de_la_CAF_montant', null, [
                'attr' => [
                    'class' => 'input-small'
                ]
            ])            

            ->add('RBT_motif', null, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'input-medium'
                ]
            ])
            ->add('RBT_date', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input-medium'
                ]
            ])
            ->add('RBT_mode', ChoiceType::class, [
                'choices' => [
                    'VIR' => 'VIR',
                    'ESP' => 'ESP',
                    'CB' => 'CB',
                    'CHQ' => 'CHQ',
                    'Autres' => 'Autres',
                ],
                'data' => 'VIR',
                'required' => false,
                'placeholder' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'input-small'
                ]
            ])
            ->add('RBT_montant', null, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'input-small'
                ]
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
