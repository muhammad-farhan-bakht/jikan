<?php

namespace Jikan\Parser\Character;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharacterParser
 *
 * @package Jikan\Parser
 */
class CharacterParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     *
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Character
    {
        return Model\Character::fromParser($this);
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getCharacterUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCharacterUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:title"]')->attr('content');
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNameKanji(): string
    {
        $kanji = $this->crawler->filterXPath('//div[contains(@class,"breadcrumb")]')
            ->nextAll()->filter('small')
            ->text();

        return str_replace(['(', ')'], '', $kanji);
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function getNameNicknames(): array
    {
        $aliases = preg_replace(
            '/^.*"(.*)".*$/',
            '$1',
            $this->crawler->filterXPath('//h1')->text(),
            -1,
            $count
        );
        if (!$count) {
            return [];
        }

        return explode(', ', $aliases);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAbout(): string
    {
        $crawler = Parser::removeChildNodes($this->crawler->filterXPath('//*[@id="content"]/table/tr/td[2]'));

        return JString::cleanse($crawler->text());
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMemberFavorites(): int
    {
        $crawler = $this->crawler->filterXPath('//*[@id="content"]/table/tr/td[1]');
        $crawler = Parser::removeChildNodes($crawler);

        return (int)preg_replace('/\D/', '', $crawler->text());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:image"]')->attr('content');
    }

    /**
     * @return Model\Animeography[]
     * @throws \InvalidArgumentException
     */
    public function getAnimeography(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(text(), \'Animeography\')]/../table[1]/tr')
            ->each(
                function (Crawler $c) {
                    return (new AnimeographyParser($c))->getModel();
                }
            );
    }

    /**
     * @return Model\Mangaography[]
     * @throws \InvalidArgumentException
     */
    public function getMangaography(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(text(), \'Mangaography\')]/../table[2]/tr')
            ->each(
                function (Crawler $c) {
                    return (new MangaographyParser($c))->getModel();
                }
            );
    }

    /**
     * @return Model\VoiceActor[]
     * @throws \InvalidArgumentException
     */
    public function getVoiceActors(): array
    {
        return $this->crawler
            ->filterXPath('//div[contains(text(), \'Voice Actors\')]/../table/tr')
            ->each(
                function (Crawler $c) {
                    return (new VoiceActorParser($c))->getModel();
                }
            );
    }
}
