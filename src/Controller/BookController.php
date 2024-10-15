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
        $books = $bookRepository->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/search/{id}', name: 'app_search_book')]
    public function search(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);
        var_dump($book);
        die();
        return $this->render('book/list.html.twig', [
            'books' => [$book]
        ]);
    }

    #[Route('/new', name: 'app_new_book')]
    public function new(ManagerRegistry $doctrine): Response
    {
        $book = new Book();

        $book->setRef('hgkrteze');
        $book->setTitle('My New Book');
        $book->setNbrPages(368);
        $book ->setPicture('C:\Users\yasmi\Pictures\Screenshots');
        //$book ->setPublishDate(new \DateTime('now'));
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
