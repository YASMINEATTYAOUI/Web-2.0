<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
//use App\Form\BookType; // Create a form for Book entity if needed
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/list', name: 'app_list_book')]
    public function list(BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $book
        ]);
    }

    #[Route('/search/{id}', name: 'app_search_book')]
    public function search(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);
        var_dump($book);
        die();
        return $this->render('crud_book/list.html.twig', [
            'books' => [$book]
        ]);
    }

    #[Route('/new', name: 'app_new_book')]
    public function new(ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $book->setTitle('My New Book');
        $book->setDescription('This is a description of my new book.');

        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();
        return $this->redirectToRoute('app_list_book');
    }

    #[Route('/delete/{id}', name: 'app_delete_book', methods: ['DELETE', 'GET'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('app_list_book');
    }

    #[Route('/update/{id}', name: 'app_update_book')]
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $book->setTitle('Updated Book Title');
        $book->setDescription('Updated description of the book.');
        $entityManager->flush();

        return $this->redirectToRoute('app_list_book');
    }
}
