<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;  // Correct import for ManagerRegistry
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud')]
class CrudAuthorController extends AbstractController
{

    #[Route('/list', name: 'app_list_author')]
    public function list(AuthorRepository $authorRepository): Response
    {
        // Corrected single $
        $authors = $authorRepository->findAll();

        return $this->render('crud_author/list.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route('/search/{id}', name: 'app_search_author')]
    public function search(AuthorRepository $authorRepository, int $id): Response
    {
        // Corrected single $ and fixed the type hint
        $author = $authorRepository->find($id);

        var_dump($author);
        die();

        return $this->render('crud_author/list.html.twig', [
            'authors' => [$author]
        ]);
    }

    #[Route('/new', name: 'app_new_author')]
    public function new(ManagerRegistry $doctrine): Response
    {
        // 1. Create an instance of Author
        $author = new Author();
        $author->setName('John');
        $author->setEmail('John@example.com');

        // 2. Connect with the DB
        $entityManager = $doctrine->getManager();
        $entityManager->persist($author);
        $entityManager->flush();  // No need to pass $author here

        // 3. Redirect to the list of authors
        return $this->redirectToRoute('app_list_author');
    }

    #[Route('/delete/{id}', name: 'app_delete_author', methods: ['DELETE', 'GET'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        // 1. Get the EntityManager
        $entityManager = $doctrine->getManager();

        // 2. Find the author by ID
        $author = $entityManager->getRepository(Author::class)->find($id);

        // 3. If the author is not found, throw a 404 exception
        if (!$author) {
            throw $this->createNotFoundException('No author found for id ' . $id);
        }

        // 4. Remove the author
        $entityManager->remove($author);
        $entityManager->flush();

        // 5. Redirect to the list of authors
        return $this->redirectToRoute('app_list_author');
    }

    #[Route('/update/{id}', name: 'app_update_author')]
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        // 1. Get the EntityManager
        $entityManager = $doctrine->getManager();

        // 2. Find the author by ID
        $author = $entityManager->getRepository(Author::class)->find($id);

        // 3. If the author is not found, throw a 404 exception
        if (!$author) {
            throw $this->createNotFoundException('No author found for id ' . $id);
        }

        // 4. Update the author details (hardcoded here; typically from form input)
        $author->setName('Yasmine');
        $author->setEmail('yasmine@example.com');

        // 5. Save the changes to the database
        $entityManager->flush();

        // 6. Redirect to the list of authors or another appropriate page
        return $this->redirectToRoute('app_list_author');
    }


}
