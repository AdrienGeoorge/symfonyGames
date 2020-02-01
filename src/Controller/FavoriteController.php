<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FavoriteController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class FavoriteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/favorite", name="favorite")
     */
    public function index()
    {
        $favorites = $this->getUser()->getFavorites();

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    /**
     * @Route("/favorite/add/{id}", name="favorite_add")
     * @ParamConverter("game", options={"id"="id"})
     * @param Game $game
     * @return Response
     */
    public function add(Game $game)
    {
        $this->getUser()->addFavorite($game);
        $this->em->persist($this->getUser());
        $this->em->flush();

        $this->addFlash('success', 'Favorite added.');

        return $this->redirectToRoute('favorite');
    }

    /**
     * @Route("/favorite/remove/{id}", name="favorite_remove")
     * @ParamConverter("game", options={"id"="id"})
     * @param Game $game
     * @return Response
     */
    public function remove(Game $game)
    {
        $this->getUser()->removeFavorite($game);
        $this->em->persist($this->getUser());
        $this->em->flush();

        $this->addFlash('success', 'Favorite removed.');

        return $this->redirectToRoute('favorite');
    }
}
