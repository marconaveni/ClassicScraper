<?php


class Game
{
    private int $id;
    private string $title;
    private string $description;
    private string $developer;
    private string $publisher;
    private string $releaseDate;
    private string $players;
    private string $genre;
    private string $cover;
    private string $screenshot;
    private string $video;


    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getDeveloper(): string
    {
        return $this->developer;
    }
    public function getPublisher(): string
    {
        return $this->publisher;
    }
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }
    public function getPlayers(): string
    {
        return $this->players;
    }
    public function getGenre(): string
    {
        return $this->genre;
    }
    public function getCover(): string
    {
        return $this->cover;
    }
    public function getScreenshot(): string
    {
        return $this->screenshot;
    }
    public function getVideo(): string
    {
        return $this->video;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    public function setDeveloper(string $developer)
    {
        $this->developer = $developer;
    }
    public function setPublisher(string $publisher)
    {
        $this->publisher = $publisher;
    }
    public function setReleaseDate(string $releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }
    public function setPlayers(string $players)
    {
        $this->players = $players;
    }
    public function setGenre(string $genre)
    {
        $this->genre = $genre;
    }
    public function setCover(string $cover)
    {
        $this->cover = $cover;
    }
    public function setScreenshot(string $screenshot)
    {
        $this->screenshot = $screenshot;
    }
    public function setVideo(string $video)
    {
        $this->video = $video;
    }
}
