<?php

namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea');

        // Le nom de la zone de texte ("content") n'est pas choisi au hasard : il correspond exactement à la propriété content de la classe métier Comment. C'est indispensable pour que Symfony puisse associer notre formulaire à une instance de Comment.
    }

    public function getName()
    {
        return 'comment';
    }
}