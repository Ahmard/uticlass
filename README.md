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

### Videos
Extract movies/tv shows download links
```php
use Uticlass\Video\CoolMoviez;
use Uticlass\Video\FZMovies;
use Uticlass\Video\NetNaija;

require 'vendor/autoload.php';

//Extract links and data

//Fzmovies
$fzUrl = 'https://fzmovies.net/movie-Moana%202016--hmp4.htm';
$dlLink1 = FZMovies::init($fzUrl)->get();

//Netnaija
$nnUrl = 'https://www.thenetnaija.com/videos/movies/6856-hot-summer-nights-2017';
$dlLink2 = NetNaija::init($nnUrl)->get()->linkTwo();

//Mycoolmoviez
$mcUrl = 'https://www.coolmoviez.shop/movie/4715/Megafault_(2009)_english_movie.html';
$dlLink3 = CoolMoviez::init($mcUrl)->get();
```

### Others
Extract links from file storage and lyric sites
```php
use Uticlass\Lyrics\Genius;
use Uticlass\Others\ZippyShare;
use Uticlass\Others\FireFiles;

//Genius lyrics
$lyrics = Genius::init('https://genius.com/Taylor-swift-the-last-great-american-dynasty-lyrics')->get();

//ZippyShare
$zippyUrl = 'https://www118.zippyshare.com/v/5pwuoWgg/file.html';
$fileUrl = ZippyShare::init($zippyUrl)->get();

//FireFiles
$webpageUrl = 'https://firefiles.org/5m6rzmnb7v54'; //The Mandalorian S01E06
$fileLink = FireFiles::init($webpageUrl)->get();
```

### Searching
Search movies sites
```php
use Uticlass\Video\Search\FZMoviesSearch;
use Uticlass\Video\Search\FEMKVComSearch;
use Uticlass\Video\Search\NetNaijaSearch;

require 'vendor/autoload.php';

//FZMovies
$searchResults = FZMoviesSearch::create()
    ->search('wrong')
    ->get(2);

//480mkv.com
$searchResults = FEMKVComSearch::create()
    ->search('love')
    ->get();

//NetNaija.com
$searchResults = NetNaijaSearch::create()
    ->search('love')
    ->category(NetNaijaSearch::CAT_VIDEOS)
    ->get(3);
```
## [Examples](examples)

### No Licence :)