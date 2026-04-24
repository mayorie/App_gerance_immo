<?php

namespace App\Form;

use App\Entity\Commentaires;
use App\Entity\Logements;
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
