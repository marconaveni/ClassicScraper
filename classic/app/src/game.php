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
    public Platform $platform;

    /* This is the static comparing function: */
    public static function compareId($a, $b)
    {
        return strtolower($a->id) <=> strtolower($b->id);
    }


}
