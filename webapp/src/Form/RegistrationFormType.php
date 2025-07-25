<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{

    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(
                        message: $this->translator->trans('constraints.password.blank', [], 'validation'),
                    ),
                    new Length(
                        min: 6,
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                        minMessage: $this->translator->trans('constraints.password.min', [], 'validation'),
                        maxMessage: $this->translator->trans('constraints.password.max', [], 'validation'),
                    ),
                ],
            ])
            ->add('fullName');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
