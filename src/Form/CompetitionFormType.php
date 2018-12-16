<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\DTO\CompetitionDTO;
use Symfony\Component\Form\DataMapperInterface;

class CompetitionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name')
            ->add('homeVisitor')
            ->add('location')
            ->add('tag')
            ->add('competitor')
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => CompetitionDTO::class,
            'empty_data' => null,
        ]);
    }
    
    public function mapDataToForms($competitionDTO, $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['name']->setData($competitionDTO ? $competitionDTO->getName() : '');
        $forms['homeVisitor']->setData($competitionDTO ? $competitionDTO->getHomeVisitor() : '');
        $forms['location']->setData($competitionDTO ? $competitionDTO->getLocation() : '');
        $forms['tag']->setData($competitionDTO ? $competitionDTO->getTag() : '');
        $forms['competitor']->setData($competitionDTO ? $competitionDTO->getCompetitor() : '');
    }
    
    public function mapFormsToData($forms, &$competitionDTO): void
    {
        $forms = iterator_to_array($forms);
        $competitionDTO = CompetitionDTO::create(
            $forms['name']->getData(),
            $forms['homeVisitor']->getData(),
            $forms['location']->getData(),
            $forms['tag']->getData(),
            $forms['competitor']->getData()
        );
    }
}
