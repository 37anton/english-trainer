<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Form\TraductionFormType;
use App\Repository\VocabularyWordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayController extends AbstractController
{
    #[Route('/play', name: 'play_play')]
    public function play(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TraductionFormType::class);

        return $this->render('play/play.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/game/start', name: 'play_game_start')]
    public function start(Request $request, EntityManagerInterface $em, VocabularyWordRepository $vocabularyWordRepository): Response
    {

        $player = $this->getUser();

        $game = new Game();
        $game->setDate(new \DateTime());
        $game->setPlayer($player);
        $game->setScore(0);

        $wordsList = $vocabularyWordRepository->findRandomWords(5);

        $wordIds = array_map(fn ($word) => $word->getId(), $wordsList);

        $game->setWordsId($wordIds);

        $em->persist($game);
        $em->flush();

        $firstWord = $wordsList[0];

        return $this->json([
            'message' => 'Partie commencée',
            'gameId' => $game->getId(),
            'firstWord' => [
                'id' => $firstWord->getId(),
                'translation' => $firstWord->getFrenchWord(),
            ],

        ]);
    }

    #[Route('/verify/translation', name: 'verify_translation')]
    public function verifyTranslation(Request $request, EntityManagerInterface $em, VocabularyWordRepository $vocabularyWordRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $wordId = $data['wordId'];
        $userTranslation = $data['userTranslation'];

        $word = $vocabularyWordRepository->find($wordId);

        if ($word && $word->getEnglishWord() === $userTranslation) {
            // Logique pour gérer une réponse correcte
            return $this->json(['correct' => true]);
        } else {
            // Logique pour gérer une réponse incorrecte
            return $this->json(['correct' => false]);
        }
    }
}
