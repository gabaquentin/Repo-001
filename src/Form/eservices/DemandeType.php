<?php

namespace App\Form\eservices;

use App\Entity\CategorieService;
use App\Entity\Demande;
use App\Repository\CategorieServiceRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DemandeType extends AbstractType
{
    private $categorieRepo;

    public function __construct(CategorieServiceRepository $repo)
    {
        $this->categorieRepo = $repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localisation',TextType::class)
            ->add("description",TextareaType::class)
            ->add('date')
            ->add('photo', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'SVP insérer une image valide (format jpg, jpeg et png)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
