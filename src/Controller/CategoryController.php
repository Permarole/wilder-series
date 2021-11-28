<?php

// src/Controller/CategoryController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;

use App\Entity\Program;

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
