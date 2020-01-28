<?php

	namespace App\Controller;

	use App\Entity\Game;
	use App\Form\GameType;
	use App\Repository\GameRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	class GameController extends AbstractController
	{
		private $em;

		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
		}

		/**
		 * @Route("/games/list", name="game_list")
		 * @param GameRepository $gameRepository
		 *
		 * @return Response
		 */
		public function index(GameRepository $gameRepository): Response
		{
			$gameList = $gameRepository->findAll();
			return $this->render('game/index.html.twig', [
				'games' => $gameList
			]);
		}

		/**
		 * @Route("/game/infos/{id}", name="game_infos")
		 * @ParamConverter("game", options={"id"="id"})
		 * @param Game $game
		 *
		 * @return Response
		 */
		public function getInfos(Game $game): Response
		{
			return $this->render('game/infos.html.twig', [
				'games' => $game
			]);
		}
		/**
		 * @Route("/game/new", name="game_new")
		 * @param Request $request
		 * @return Response
		 *
		 */
		public function addGame(Request $request): Response
		{
			$Game = new Game();
			$form = $this->createForm(GameType::class, $Game);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$this->em->persist($Game);
				$this->em->flush();
				$this->addFlash('success', 'Game created.');
			}
			return $this->render('game/form.html.twig', [
				'form' => $form->createView()
			]);
		}

		/**
		 * @Route("/game/edit/{id}", name="game_edit")
		 * @ParamConverter("game", options={"id"="id"})
		 * @param Request $request
		 * @param Game $game
		 *
		 * @return Response
		 * @IsGranted("ROLE_ADMIN")
		 */
		public function editEditor(Request $request, Game $game): Response
		{
			$form = $this->createForm(GameType::class, $game);
			$form->handleRequest($request);

			if($form->isSubmitted() && $form->isValid()){
				$this->em->persist($game);
				$this->em->flush();

				$this->addFlash('success', 'Game updated.');
			}

			return $this->render('game/form.html.twig', [
				'form' => $form->createView()
			]);
		}

		/**
		 * @Route("/game/delete/{id}", name="game_delete")
		 * @ParamConverter("game", options={"id"="id"})
		 * @param Game $Game
		 * @return Response
		 * @IsGranted("ROLE_ADMIN")
		 */
		public function deleteEditor(Game $Game): Response
		{
			$this->em->remove($Game);
			$this->em->flush();

			$this->addFlash('success', 'Game deleted.');

			return $this->redirectToRoute('game_list');
		}

	}
