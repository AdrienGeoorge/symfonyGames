<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\RegistrationFormType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user/list", name="list_user")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        $userRepository = $userRepository->findAll();

        return $this->render('admin/infos.html.twig', [
            'users' => $userRepository,
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     * @ParamConverter("user", options={"id"="id"})
     * @param Request $request
     * @param User $User
     * @return Response
     */
    public function editUser(Request $request, User $User): Response
    {
        $form = $this->createForm(UserEditType::class, $User);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($User);
            $this->em->flush();
            $this->addFlash('success', 'User updated.');
        }
        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/add", name="user_add")
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    public function insertUser(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $User = new User();

        $form = $this->createForm(RegistrationFormType::class, $User);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $User->setPassword(
                $this->passwordEncoder->encodePassword(
                    $User,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($User);
            $this->em->flush();
            $this->addFlash('success', 'User created.');

            $userRegisterEvent = new UserRegisterEvent($User);
            $eventDispatcher->dispatch($userRegisterEvent);
        }
        return $this->render('admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @ParamConverter("user", options={"id"="id"})
     * @param User $User
     * @return Response
     */
    public function deleteUser(User $User): Response
    {
        $this->em->remove($User);
        $this->em->flush();
        $this->addFlash('success', 'User deleted.');

        return $this->redirectToRoute('admin_list_user');
    }
}
