<?php


namespace AppBundle\Form\Type;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class CommentaireType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('content',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
        ->add('send',SubmitType::class);
    //parent::buildForm($builder, $options);
}
}


