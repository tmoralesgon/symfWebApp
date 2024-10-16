<?php
namespace App\Controller\UserCli;

use App\Entity\UserCli;
use App\Form\UserCliType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Matcher\Dumper;


class UserCliController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('usercli/new')]
    public function new(Request $request): Response
    {
        $userCli = new UserCli();
        $form = $this->createForm(UserCliType::class, $userCli);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $userCli->setFirstName($formData['firstName']);
            $userCli->setLastName($formData['lastName']);
            $userCli->setEmail($formData['email']);
            $userCli->setCountry($formData['country']);
            $userCli->setBirthday($formData['birthday']);

            $this->entityManager->persist($userCli);
            $this->entityManager->flush();

            $this->addFlash('success', 'User successfully created!');
            return $this->redirectToRoute('usercli/new');
        }
        return $this->render('userCli/new.html.twig', [
            'form' => $form,
        ]);
    }
}