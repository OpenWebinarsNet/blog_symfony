<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,array(
                'label'  => 'Post Title',
                'required'  => true,
                'constraints' => new Length(array('max' => 255)),
            ))
            ->add('slug')
            ->add('description')
            ->add('dueDate', null, array(
                'mapped' => false
            ))
//            ->add('regDate')
//            ->add('modDate')
//            ->add('user')
//            ->add('categories',CategoryType::class)
            ->add('save', SubmitType::class);
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Post',
//            'validation_groups' => array('edit'),
//        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
