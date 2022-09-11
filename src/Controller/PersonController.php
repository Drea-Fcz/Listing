<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('person')]
class PersonController extends AbstractController
{
    private EntityManagerInterface $_em;
    private PersonRepository $repository;

    public function __construct(EntityManagerInterface $em,
                                PersonRepository $repository
    )
    {
        $this->_em = $em;
        $this->repository = $repository;
    }

//    #[Route('/add', name: '_person.add')]

    /**
     * @return void
     */
    public function addPerson()
    {
        $person =  new Person();
        $person->setName('Fcz')
            ->setFirstname('Audrey')
            ->setAge(38)
            ->setJob('Developer');

        $this->_em->persist($person);
        $this->_em->flush();
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
            'isPaginated' => true
        ]);
    }

    /**
     * @param $page
     * @param $nbr
     * @return Response
     */
    #[Route('/all/{ageMin?22}/{ageMax?33}', name: '_person.age')]
    public function displayAllPersonsByAges($ageMin, $ageMax): Response
    {
        $persons = $this->repository->findPersonByIntervalAge($ageMin,$ageMax);
        $stats = $this->repository->statPersonByIntervalAgeAndMoyen($ageMin,$ageMax);

        return $this->render('person/detail.html.twig', [
            'persons' => $persons,
            'stat' => $stats,
            'isPaginated' => true
        ]);
    }

    /**
     * @param $page
     * @param $nbr
     * @return Response
     */
    #[Route('/all/{page?1}/{nbr?12}', name: '_person.all')]
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
