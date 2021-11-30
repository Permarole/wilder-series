<?php

// src/Controller/CategoryController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;

use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**

 * @Route("/category", name="category_")

 */
class CategoryController extends AbstractController
{
    /**

     * @Route("/", name="index")

     * @return Response A response instance

     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()

            ->getRepository(Category::class)

            ->findAll();


        return $this->render(

            'category/index.html.twig',

            ['categories' => $categories]

        );
    }

    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list 
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}",  name="show")
     * @return Response A response instance
     */
    public function show(string $name): Response
    {

        $category = $this->getDoctrine()

            ->getRepository(Category::class)

            ->findOneBy(['name' => $name]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : ' . $name . ' found in category\'s table.'
            );
        }

        $programs = $this->getDoctrine()

            ->getRepository(Program::class)

            ->findBy(['category' => $category->getId()], ['id' => 'DESC'], 3);


        if (!$programs) {

            throw $this->createNotFoundException(

                'No program in category : ' . $name . ' found in category\'s table.'

            );
        }

        return $this->render('category/show.html.twig', [

            'programs' => $programs,
            'category' => $category

        ]);
    }
}
