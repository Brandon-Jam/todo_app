<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class TaskForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite'
            ])
            ->add('tags', EntityType::class, [
            'class' => Tag::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true, // ou false pour un select multiple
            'required' => false,
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('t')
                    ->where('t.owner IS NULL OR t.owner = :user')
                    ->setParameter('user', $user);
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'user' => null,
        ]);
    }
}
