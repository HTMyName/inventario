<?php

namespace App\Form;

use App\Entity\Facturas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('id_cliente', NumberType::class, [
				'attr' => [
					'readonly' => 'readonly',
					'value' => 'null',
					'class' => 'd-none'
				],
				'label' => ' ',
				'label_attr' => [
					'class' => 'd-none'
				]
			])
			->add('submit', SubmitType::class);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Facturas::class,
		]);
	}
}
