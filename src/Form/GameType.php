<?php

	namespace App\Form;

	use App\Entity\Editor;
	use App\Entity\Game;

	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;


	class GameType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('title', TextType::class)
				->add('description', TextType::class)
				->add('releaseDate', DateType::class, [
					'widget' => 'choice',
					'format' => 'dMy',
                    'years' => range(date('Y'), date('Y') - 50)
				])
				->add('editor', EntityType::class, [
					'class' => Editor::class,
					'choice_label' => function (Editor $editor) {
						return $editor->getBuisnessName();
					}
				])
				->add('submit', SubmitType::class);
		}

		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Game::class,
			]);
		}
	}
