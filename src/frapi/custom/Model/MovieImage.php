<?php
namespace Score11\Frapi;

require_once CUSTOM_MODEL . DIRECTORY_SEPARATOR . 'Config.php';

class MovieImage
{
    private $_movieId;
    
    private $_hasImage;
    
    private $_movieImagePath;
    
    public function __construct($movieId, $hasImage = 'y')
    {
        $this->_movieId = $movieId;
        $this->_hasImage = $hasImage;
        $config = new Config();
        $host = $config->getConfig('host');
        $path = $config->getConfig('movieimgpath');
        $this->_movieImagePath = $host . '/' . $path;
    }
    
    public function getLink()
    {
        if (!$this->_hasImage) {
            $link = sprintf('%s/25/6025', $this->_movieImagePath);
        } else {
            $lastTwo = substr($this->_movieId, -2, 2);
            // %s statt %d fuer den zweiten paraemter verwendet, da sonst aus 05 5 wird und dann stimmt der Bildlink nicht mehr
            $link = sprintf('%s/%s/%s', $this->_movieImagePath, $lastTwo, $this->_movieId);
        }
        return $link;
    }
}
