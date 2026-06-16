<?php

namespace App\Form;

use App\Entity\Garants;
use App\Entity\GarantsPhysiques;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class GarantsPhysiquesSousFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('mail')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('date_de_naissance', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])        
            ->add('lieu_de_naissance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GarantsPhysiques::class,
        ]);
    }
}
