<?php

namespace App\Form;

use App\Entity\Commentaires;
use App\Entity\Logements;
use App\Entity\Pcg;
use App\Repository\PcgRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_appart')
            ->add('residence')
            ->add('batiment')
            ->add('appt')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('SIRET')
            ->add('num_chambre')
            ->add('pcgPrestation', EntityType::class, [
                'class' => Pcg::class,
                'choice_label' => function (Pcg $pcg) {
                    return $pcg->getCompte() . ' - ' . $pcg->getLibelle();
                },
                'label' => 'Compte comptable (7)',
                'required' => false,
                'placeholder' => 'Sélectionner un compte',
                'query_builder' => function (PcgRepository $repo) {
                    return $repo->createQueryBuilder('p')
                        ->orderBy('p.compte', 'ASC');
                },
            ])
            ->add('commentaire', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'data' => $options['commentaire_data'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logements::class,
            'commentaire_data' => null,
        ]);
    }
}
