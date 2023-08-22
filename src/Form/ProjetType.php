<?php

namespace App\Form;
use App\Entity\Projet;
use App\Repository\MaisonDeCulteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    private $maisonDeCulteRepository;

    public function __construct(MaisonDeCulteRepository $maisonDeCulteRepository)
    {
        $this->maisonDeCulteRepository = $maisonDeCulteRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('montant')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('statut');
         
    }

    private function getMaisonDeCulteIds()
    {
        $maisonsDeCulte = $this->maisonDeCulteRepository->findAll();

        $maisonDeCulteIds = [];
        foreach ($maisonsDeCulte as $maisonDeCulte) {
            $maisonDeCulteIds[] = $maisonDeCulte->getId();
        }

        return $maisonDeCulteIds;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}