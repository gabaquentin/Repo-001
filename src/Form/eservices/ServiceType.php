<?php

namespace App\Form\eservices;

use App\Entity\CategorieService;
use App\Entity\Service;
use App\Repository\CategorieServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ServiceType extends AbstractType
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
            ->add('nbreQuestions')
            ->add('description', TextareaType::class)
            ->add('categorieService',EntityType::class,[
                "class"=>CategorieService::class,
                "choice_label"=>"nom",
                "choices"=>$this->categorieRepo->findAll(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
