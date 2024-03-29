<?php

namespace App\Controller;

use App\Entity\Person;
use App\Event\AddPersonEvent;
use App\Event\ListAllPersonEvent;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Service\MailerService;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('person'),
    isGranted('ROLE_USER')
]
class PersonController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $_em,
        private PersonRepository $repository,
        private EventDispatcherInterface $dispatcher
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[
        Route('/edit/{id?0}', name: '_person.edit'),
        isGranted('ROLE_ADMIN')
    ]
    public function editPerson(Person $person = null,
                               Request $request,
                               UploaderService $uploader,
                               MailerService  $mailerService
    ): Response
    {


        $new = false;
        // $person est l'image de notre formulaire
        if (!$person) {
            $new = true;
            $person = new Person();
        }

        $form = $this->createForm(PersonType::class, $person);

        // récupère l'objet request et extrait les informations saisies
        $form->handleRequest($request);

        // est ce que le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid())
        {
            $imgFile = $form->get('image')->getData();

            if ($imgFile) {
                $directoryFolder = $this->getParameter('images_directory');
                $person->setImg($uploader->uploadFile($directoryFolder, $imgFile));
            }
            $message = $new ? "La personne a bien été ajouté dans la liste" : "La personne a bien été modifié dans la liste";
            $person->setCreatedBy($this->getUser());

            // si oui, on ajoute l'objet person dans la base de données
            $this->_em->persist($person);
            $this->_em->flush();

            // Event Listener
            if ($new) {
                // create new event with AddPersonEvent
                $addPersonEvent = new AddPersonEvent($person);
                // We need to dispatch the event
                $this->dispatcher->dispatch($addPersonEvent, AddPersonEvent::ADD_PERSON_EVENT);
            }

            $this->addFlash('Success', $message);
            // Rediriger vers la liste des personnes
            return $this->redirectToRoute('_person.list');
        } else {
            return $this->render('person/add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }


    /**
     * @return Response
     */
    #[Route('', name: '_person.list')]
    public function displayPersons(): Response
    {
        $persons = $this->repository->findAll();

        return $this->render('person/detail.html.twig', [
            'persons' => $persons,
            'isPaginated' => true,
            'isStat' => false
        ]);
    }

   /* #[Route('/pdf/{id}', name: 'person.pdf')]
    public function generatePdf(Person $person = null, PdfService $pdf) {
        $html = $this->render('person/info.html.twig', [ 'person' => $person]);
        $pdf->showPdfFile($html);
    }*/

    /**
     * @param $page
     * @param $nbr
     * @return Response
     */
    #[Route('/all/avg/{ageMin?22}/{ageMax?33}', name: '_person.age')]
    public function displayAllPersonsByAges($ageMin, $ageMax): Response
    {
        $persons = $this->repository->findPersonByIntervalAge($ageMin,$ageMax);
        $stats = $this->repository->statPersonByIntervalAgeAndMoyen($ageMin,$ageMax);
        $listAllPersonEvent = new ListAllPersonEvent(count($persons));
        $this->dispatcher->dispatch($listAllPersonEvent, ListAllPersonEvent::LIST_ALL_PERSON_EVENT);
        return $this->render('person/detail.html.twig', [
            'persons' => $persons,
            'stat' => $stats,
            'isPaginated' => true,
            'isStat' => true
        ]);
    }

    /**
     * @param $page
     * @param $nbr
     * @return Response
     */
    #[Route('/all/avg/{page?1}/{nbr?12}', name: '_person.all')]
    public function displayAllPersons($page, $nbr): Response
    {
        $persons = $this->repository->findBy([], [], $nbr, ($page - 1) * $nbr);

        return $this->render('person/detail.html.twig', [
            'persons' => $persons,
            'isPaginated' => true
        ]);
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/detail/{id}', name: '_person.detail')]
    public function displayPerson($id)
    {
        $person = $this->repository->find($id);
        if (!$person) {
            $this->addFlash('error', "L'id : $id n'existe pas dans la base de données");
        }

        return $this->render('person/info.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @param Person|null $person
     * @return RedirectResponse
     */
    #[Route('/delete/{id}', name: '_person.delete')]
    public function deletePerson(Person $person = null): RedirectResponse
    {
        if ($person) {
            $this->_em->remove($person);
            $this->_em->flush();

            $this->addFlash('success', "La personne a bien été supprimée");

        } else {

            $this->addFlash('error', "La personne n'existe pas dans le système");
        }

       return  $this->redirectToRoute('_person.all');
    }


    /**
     * @param Person|null $person
     * @param $name
     * @param $firstname
     * @param $age
     * @return RedirectResponse
     */
    #[Route('/update/{id}/{name}/{firstname}/{age}', name: '_person.update')]
    public function updatePerson(Person $person = null, $name, $firstname, $age): RedirectResponse
    {
        // check if the person exists
        if ($person) {
            // give the new params to the object
            $person->setName($name)
                ->setFirstname($firstname)
                ->setAge($age);

            $this->_em->persist($person);
            $this->_em->flush();

            // flash message for the success
            $this->addFlash('success', "La personne a bien été modifiée");
        } else {

            // flash message if something wrong happened
            $this->addFlash('error', "La personne n'existe pas dans le système");
        }
        // If you redirect the route don't forget the response
        return  $this->redirectToRoute('_person.all');
    }


}
