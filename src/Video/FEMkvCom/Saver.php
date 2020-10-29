<?php


namespace Uticlass\Video\FEMkvCom;


class Saver
{
    protected static array $episodes;

    protected static string $url;

    protected static string $path;


    public static function save(array $episodes, string $url, string $path)
    {
        self::$episodes = $episodes;
        self::$path = $path;
        self::$url = $url;

        self::methodOne();
    }

    public static function methodOne()
    {
        $fileName = self::getFileName();
        $displayName = substr($fileName, 0, -5);
        $displayName = str_replace('-', ' ', $displayName);
        $displayName = ucwords($displayName);
        $html = 'TV Show: <a href="'.self::$url.'">'.$displayName.'</a><hr/>';
        $html .= 'Generated on: ' . date('H:i:s d/m/Y') . '<hr/>';
        $html .= '<ol>';
        foreach (self::$episodes as $episode) {
            $episode = (array)$episode;
            $html .= '<li>' . $episode['name'] . '</li>';
            $html .= '<ul>';
            foreach ($episode['links'] as $link) {
                $html .= '<li><a href="' . $link['href'] . '" target="_blank">' . $link['name'] . '</a></li>';
            }
            $html .= '</ul>';
        }
        $html .= '</ol>';

        if (self::$path[strlen(self::$path) - 1] != DIRECTORY_SEPARATOR) {
            self::$path .= DIRECTORY_SEPARATOR;
        }

        file_put_contents(self::$path . $fileName, $html);
    }


    public static function getFileName()
    {
        return explode('/', self::$url)[3] . '.html';
    }
}
