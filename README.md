# Uticlass
Pieces of classes jam-packed to ease day-to-day life :)


# Installation

Make sure that you have composer installed
[Composer](http://getcomposer.org).

If you don't have Composer run the below command
```bash
curl -sS https://getcomposer.org/installer | php
```

Now, let's install uticlas:

```bash
composer require ahmard/uticlass
```

# Usage
```php
use Uticlass\Others\ZippyShare;
use Uticlass\Video\FZMovies;
use Uticlass\Lyrics\Genius;
use Uticlass\Video\NetNaija;

require 'vendor/autoload.php';

//Extract links and data

//Fzmovies
$fzUrl = 'https://fzmovies.net/movie-Moana%202016--hmp4.htm';
$dlLink1 = FZMovies::init($fzUrl)->get();

//Netnaija
$nnUrl = 'https://www.thenetnaija.com/videos/movies/6856-hot-summer-nights-2017';
$dlLink2 = NetNaija::init($nnUrl)->get()->linkTwo();

//Genius lyrics
$lyrics = Genius::init('https://genius.com/Taylor-swift-the-last-great-american-dynasty-lyrics')->get();

//ZippyShare
$zippyUrl = 'https://www118.zippyshare.com/v/5pwuoWgg/file.html';
$fileUrl = ZippyShare::init($zippyUrl)->get();
```

## [Examplez](examples)

### USIHOYUSEF :)