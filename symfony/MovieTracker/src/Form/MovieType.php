<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Movie title'])
            ->add('director', TextType::class, ['label' => 'Director'])
            ->add('genre', ChoiceType::class, ['label' => 'Genre', 'choices' => ['Action' => 'Action', 'Comedy' => 'Comedy', 'Drama' => 'Drama', 'Horror' => 'Horror', 'Sci-fi' => 'Sci-fi', 'Thriller' => 'Thriller', 'Animation' => 'Animation', 'Documentary' => 'Documentary'], 'placeholder' => 'Choose genre'])
            ->add('releaseYear', IntegerType::class, ['label' => 'Release Year', 'attr' => ['min' => 1888, 'max' => 2035]])
            ->add('rating', IntegerType::class, ['label' => 'Rating 1-10', 'attr' => ['min' => 1, 'max' => 10]])
            ->add('watched', CheckboxType::class, ['label' => 'Already watched', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
