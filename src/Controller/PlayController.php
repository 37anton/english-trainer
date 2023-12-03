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
            'wordsList' => array_map(fn ($word) => [
                'id' => $word->getId(),
                'translation' => $word->getFrenchWord()
            ], $wordsList),
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

    #[Route('/game/update-score', name: 'game_update_score')]
    public function updateScore(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $gameId = $data['gameId'];
        $finalScore = $data['finalScore'];

        $game = $em->getRepository(Game::class)->find($gameId);
        if ($game) {
            $game->setScore($finalScore);
            $em->flush();
            return $this->json(['message' => 'Score mis à jour']);
        }

        return $this->json(['error' => 'Partie non trouvée'], 404);
    }
}
