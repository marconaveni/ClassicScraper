<?php

namespace classic\app\src;

// require_once "publisher.php";
// require_once "developer.php";
// require_once "genres.php";

class Game
{
    public int $id;
    public string $title;
    public string $description;
    public Developer $developer;
    public Publisher $publisher;
    public string $releaseDate;
    public string $players;
    public Genres $genres;
    public string $cover;
    public string $screenshot;
    public string $video;

    /* This is the static comparing function: */
    public static function compareId($a, $b)
    {
        return strtolower($a->id) <=> strtolower($b->id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $value)
    {
        $this->title = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }

    public function getDeveloper(): Developer
    {
        return $this->developer;
    }

    public function setDeveloper(Developer $value)
    {
        $this->developer = $value;
    }

    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(Publisher $value)
    {
        $this->publisher = $value;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $value)
    {
        $this->releaseDate = $value;
    }

    public function getPlayers(): string
    {
        return $this->players;
    }

    public function setPlayers(string $value)
    {
        $this->players = $value;
    }

    public function getGenres(): Genres
    {
        return $this->genres;
    }

    public function setGenres(Genres $value)
    {
        $this->genres = $value;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $value)
    {
        $this->cover = $value;
    }

    public function getScreenshot(): string
    {
        return $this->screenshot;
    }

    public function setScreenshot(string $value)
    {
        $this->screenshot = $value;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function setVideo(string $value)
    {
        $this->video = $value;
    }
}
