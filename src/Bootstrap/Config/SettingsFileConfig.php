<?php

namespace Codemastercarlos\Receipt\Bootstrap\Config;

class SettingsFileConfig
{
    public static function addSettingsFilesInSuperGlobal(): void
    {
        self::traversingFilesFromTheSettingsDirectory();
    }

    private static function dirPath(): string
    {
        return __DIR__ . '/../../../config/';
    }

    private static function traversingFilesFromTheSettingsDirectory(): void
    {
        $directory = dir(self::dirPath());
        while (false !== ($file = $directory->read())) {
            self::addConfigurationFileToSuperGlobal($file);
        }
        $directory->close();
    }

    private static function addConfigurationFileToSuperGlobal($file): void
    {
        if ($file === "." || $file === "..") {
            return;
        }

        $name = self::clearFileName($file);

        $GLOBALS[$name] = require self::dirPath() . $file;
    }

    private static function clearFileName(string $fileName): string
    {
        preg_match("/^(.*).php/", $fileName, $matches);
        $fileNameClass = $matches[1];
        $firstWord = $fileNameClass[0];

        return str_replace($firstWord, strtolower($firstWord), $fileNameClass);
    }
}