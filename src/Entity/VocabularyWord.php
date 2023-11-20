<?php

namespace App\Entity;

use App\Repository\VocabularyWordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VocabularyWordRepository::class)]
class VocabularyWord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $englishWord = null;

    #[ORM\Column(length: 255)]
    private ?string $frenchWord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnglishWord(): ?string
    {
        return $this->englishWord;
    }

    public function setEnglishWord(string $englishWord): static
    {
        $this->englishWord = $englishWord;

        return $this;
    }

    public function getFrenchWord(): ?string
    {
        return $this->frenchWord;
    }

    public function setFrenchWord(string $frenchWord): static
    {
        $this->frenchWord = $frenchWord;

        return $this;
    }
}
