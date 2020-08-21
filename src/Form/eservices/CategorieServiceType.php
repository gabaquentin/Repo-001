<?php

namespace App\Form\eservices;

use App\Entity\CategorieService;
use App\Repository\CategorieServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class   CategorieServiceType extends AbstractType
{
    private $categorieRepo;

    public function __construct(CategorieServiceRepository $repo)
    {
        $this->categorieRepo = $repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description', TextareaType::class)
            ->add('imgfile', FileType::class, [
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
            ])
            ->add('categorieParent',EntityType::class,[
                "class"=>CategorieService::class,
                "choice_label"=>"nom",
                "choices"=>$this->categorieRepo->findAll(),
                ])
            ->add('typeCategorie', CheckboxType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategorieService::class,
        ]);
    }
}
