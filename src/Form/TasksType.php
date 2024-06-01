<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TasksType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('content', null, [
                'label' => 'Contenu'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary btn-lg'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
