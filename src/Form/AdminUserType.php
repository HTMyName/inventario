<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class,
				[
					'label' => 'Nombre'
				]
			)
			->add('username', TextType::class,
				[
					'label' => 'Usuario'
				]
			)
			->add('password', PasswordType::class,
				[
					'label' => 'Contraseña'
				]
			)
			->add('submit', SubmitType::class,
				[
					'label' => 'Enviar',
					'attr' => ['class' => 'btn btn-block btn-primary mt-4']
				]
			);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
