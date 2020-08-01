<?php

namespace App\Form\ecommerce;

use App\Entity\CategorieProd;
use App\Entity\Produit;
use App\Entity\Ville;
use App\Repository\CategorieProdRepository;
use App\Repository\VilleRepository;
use App\Services\ecommerce\Tools;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    private $repoCategorie;
    private $repoVille;
    private $tools;
    public function __construct(CategorieProdRepository $repo,Tools $tools,VilleRepository $villeRepository)
    {
        $this->repoCategorie = $repo;
        $this->tools = $tools;
        $this->repoVille = $villeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('categorieProd',EntityType::class,[
                "class"=>CategorieProd::class,
                "choice_label"=>"nomCategorie",
                "choices"=>$this->repoCategorie->findAll(),
            ])
            ->add('typeTransaction',ChoiceType::class,[
                "choices"=>$this->tools->getTypeTransaction()
            ])
            ->add('prix')
            ->add('prixPromo')
            //->add('images')
            ->add('localisation')
            ->add('visiblite')
            ->add('dureeSejour')
            ->add('meuble')
            //->add('produitsAssocies')
            //->add('attributs')
            ->add('caracteristique',CaracteristiquesType::class)
            ->add('dimension',DimensionType::class)
            ->add('ville',EntityType::class,[
                'class'=> Ville::class,
                "choice_label"=>"villes",
                'empty_data'=>'aucune',
                'choices'=> $this->repoVille->findAll(),
            ])
            //->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
