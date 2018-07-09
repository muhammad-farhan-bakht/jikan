<?php

namespace Jikan\Model\Top;

use Jikan\Model\MalUrl;
use Jikan\Parser\Top\TopListItemParser;

/**
 * Class TopCharacters
 *
 * @package Jikan\Model
 */
class TopCharacter
{
    /**
     * @var int
     */
    private $rank;

    /**
     * @var MalUrl
     */
    private $malUrl;

    /**
     * @var string
     */
    private $nameKanji;

    /**
     * @var MalUrl[]
     */
    private $animeography;

    /**
     * @var MalUrl[]
     */
    private $mangaography;

    /**
     * @var int
     */
    private $favorites;

    /**
     * @var string
     */
    private $image;

    /**
     * Create an instance from an AnimeParser parser
     *
     * @param TopListItemParser $parser
     *
     * @return self
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(TopListItemParser $parser): self
    {
        $instance = new self();
        $instance->rank = $parser->getRank();
        $instance->malUrl = $parser->getMalUrl();
        $instance->nameKanji = $parser->getCharacterKanji();
        $instance->animeography = $parser->getAnimeography();
        $instance->mangaography = $parser->getMangaography();
        $instance->favorites = $parser->getFavorites();
        $instance->image = $parser->getImage();

        return $instance;
    }

    public function __toString(): string
    {
        return $this->malUrl->getName();
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }

    /**
     * @return string
     */
    public function getNameKanji(): string
    {
        return $this->nameKanji;
    }

    /**
     * @return MalUrl[]
     */
    public function getAnimeography(): array
    {
        return $this->animeography;
    }

    /**
     * @return MalUrl[]
     */
    public function getMangaography(): array
    {
        return $this->mangaography;
    }

    /**
     * @return int
     */
    public function getFavorites(): int
    {
        return $this->favorites;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
}
