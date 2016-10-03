<?php
namespace ApiBundle\Form\Type;

use ApiBundle\Model\Register;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', TextType::class, ['property_path' => 'firstName']);
        $builder->add('last_name', TextType::class, ['property_path' => 'lastName']);
        $builder->add('username', TextType::class);
        $builder->add('email', TextType::class);
        $builder->add('password', TextType::class);
        $builder->add('client_id', TextType::class);
        $builder->add('client_secret', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Register::class,
            'csrf_protection' => false
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
