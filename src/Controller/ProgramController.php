<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Program;

use App\Entity\Season;

use App\Entity\Episode;

/**

 * @Route("/program", name="program_")

 */
class ProgramController extends AbstractController

{
    /**

     * Show all rows from Program’s entity

     *

     * @Route("/", name="index")

     * @return Response A response instance

     */

    public function index(): Response

    {

        $programs = $this->getDoctrine()

            ->getRepository(Program::class)

            ->findAll();


        return $this->render(

            'program/index.html.twig',

            ['programs' => $programs]

        );
    }
    /**
     * Getting a program by id
     *
     * @Route("/{program<^[0-9]+$>}", name="show")
     * @return Response
     */

    public function show(Program $program): Response

    {
        if (!$program) {

            throw $this->createNotFoundException(

                'No program with id : ' . $program->id . ' found in program\'s table.'

            );
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [

            'program' => $program, 'seasons' => $seasons

        ]);
    }

    /**
     * 
     * Getting a season by id
     * 
     * @Route("/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}", name="show_season")
     * 
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();

        return $this->render(
            'program/season_show.html.twig',
            ['program' => $program, 'season' => $season, 'episodes' => $episodes]
        );
    }

    /**
     * 
     * Getting an episode by id
     * 
     * @Route("/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}/episode/{episode<^[0-9]+$>}", name="show_episode")
     * 
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render(
            'program/episode_show.html.twig',
            ['program' => $program, 'season' => $season, 'episode' => $episode]
        );
    }
}
