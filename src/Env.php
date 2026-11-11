<?php

/**
 * Env.php
 * @Auteur: Christophe Dufour
 * 
 * Classe permettant de charger un fichier de configuration avec des variables d’environnement pour un projet php.
 */

declare(strict_types=1);

namespace ChrisDfr\Env;

use ChrisDfr\Env\Exception\InvalidFileException;
use SplFileObject;

class Env
{
    /**
     * Tableau des variables
     */
    private static $setting = [];

    /**
     * @param string $path
     * Chemin vers le fichier .env
     * @return void
     * Ne retourne aucune valeur
     * @throws InvalidFileException
     */
    public static function load(string $path): void
    {
        if(substr($path, -1, 1) != "/") {
            $path = $path . DIRECTORY_SEPARATOR;
        }
        
        $filenane = $path . ".env";
        $file = new SplFileObject($filenane, "r");

        while(!$file->eof()) {
            $line = trim($file->fgets());

            if(!self::analyse($line)) {
                throw new InvalidFileException(__METHOD__ . ": Format incorrect dans le fichier d'environnement ($line)");
            }
        }
    }

    /**
     * @param string $line
     * La chaine de caractère à analyser
     * @return bool
     * Retourne true en cas de succè, sinon false
     */
    protected static function analyse(string $line): bool
    {
        $pattern1 = "#^([A-Z_][A-Z0-9_]*)[\h]*=[\h]*\"(.+)\"$#";
        $pattern2 = "#^([A-Z_][A-Z0-9_]*)[\h]*=[\h]*([\S]+)$#";
        
        if(!empty($line) && !str_starts_with($line, "#")) {
            if(preg_match($pattern1, $line, $matches) || preg_match($pattern2, $line, $matches)) {
                self::$setting[trim($matches[1])] = trim($matches[2]);
            }
            else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $key
     * @param mixed $default = null
     * [Optionnel]
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if(!array_key_exists($key, self::$setting)) {
            return $default;
        }

        return self::$setting[$key];
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return self::$setting;
    }
}
