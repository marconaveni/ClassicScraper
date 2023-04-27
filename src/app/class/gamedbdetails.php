<?php

namespace classic\app\class;

// require_once "scraper.php";
// require_once 'game.php';

class GameDBDetails
{
    private function formatDescription(string $value): string
    {
        $value = explode(":", $value);
        return trim($value[1] ?? $value[0]);
    }


    private function setImages(Game $game, array $images): Game
    {
        foreach ($images as $img) {
            if (str_contains($img, 'front')) {
                $game->cover = str_replace("original", "thumb", $img);
                continue;
            }
            if (str_contains($img, 'screenshots')) {
                $game->screenshot = str_replace("original", "large", $img);
                break;
            }
        }
        return $game;
    }

    private function getInfos(Game $game, array $descriptions, array $ids): Game
    {
        foreach ($descriptions as $description) {
            if (str_contains($description, 'Developer(s):')) {
                $developer = new Developer();
                $developer->id = $ids[1] ?? 0;
                $developer->name = $this->formatDescription($description);
                $game->developer = $developer;
                continue;
            }
            if (str_contains($description, 'Publishers(s):')) {
                $publisher = new Publisher();
                $publisher->id = $ids[2] ?? 0;
                $publisher->name = $this->formatDescription($description);
                $game->publisher = $publisher;
                continue;
            }
            if (str_contains($description, 'ReleaseDate:')) {
                $game->releaseDate = $this->formatDescription($description);
                continue;
            }
            if (str_contains($description, 'Players:')) {
                $game->players = $this->formatDescription($description);
                continue;
            }
            if (str_contains($description, 'Genre(s):')) {
                $genre = new Genres();
                $genre->name = $this->formatDescription($description);
                $game->genres = $genre;
                break;
            }

        }
        return $game;
    }

    public function getDescription(Game $game, Scraper $scraper): Game
    {
        $descriptions = $scraper->query("//p[@class='game-overview']");
        $game->description = $descriptions[0];
        return $game;
    }

    public function getImages(Game $game, Scraper $scraper): Game
    {
        $images = $scraper->query("//a[@class='fancybox-thumb']/@href");
        $game = $this->setImages($game, $images);
        return $game;
    }



    public function loadGameDetails(int $id): Game
    {
        $game = new Game();

        $scraper = $this->loadHTML("https://thegamesdb.net/game.php?id=$id");

        $titles = $scraper->query("//h1");
        $descriptions = $scraper->query("//div[@class='card-body']/p");
        $links = $scraper->query("//div[@class='card-body']/p/a/@href");

        foreach ($links as $i) {
            $value = explode("id=", $i);
            $ids[] = (int)$value[1] ?? 0;
        }

        $game->id = $id;
        $game->title = $titles[0];
        $game = $this->getImages($game, $scraper);
        $game = $this->getDescription($game, $scraper);
        $game = $this->getInfos($game, $descriptions, $ids);
        return $game;
    }

    public function loadHTML(string $link): Scraper
    {
        $scraper = new Scraper();
        $scraper->loadHTML($link);
        return $scraper;
    }



}
