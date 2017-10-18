<?php

namespace Sandbox\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('name', null, ['label' => 'Nom du modèle'] )
           ->add('type')
           ->add('marque')
           ->add('created', 'date')
           ->add('Champ_annexe', null, ['mapped' => false])//permet de rajouter un champ hors de l'entite
           ->add('submit', 'submit')
           ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)//permet de deduire la dataclass en cas d'imbrication de formulaire
    {
        $resolver->setDefaults( ['data_class' => 'Sandbox\BackBundle\Entity\Car'] );
    }

    public function getName()
    {
        return 'car';
    }
}