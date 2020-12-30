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
use Uticlass\Video\CoolMoviez;
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

//Mycoolmoviez
$mcUrl = 'https://www.coolmoviez.shop/movie/4715/Megafault_(2009)_english_movie.html';
$dlLink3 = CoolMoviez::init($mcUrl)->get();

//Genius lyrics
$lyrics = Genius::init('https://genius.com/Taylor-swift-the-last-great-american-dynasty-lyrics')->get();

//ZippyShare
$zippyUrl = 'https://www118.zippyshare.com/v/5pwuoWgg/file.html';
$fileUrl = ZippyShare::init($zippyUrl)->get();
```

## Searching
```php

use Uticlass\Video\Search\FZMoviesSearch;
use Uticlass\Video\Search\FEMKVComSearch;

require 'vendor/autoload.php';

//FZMovies
$searchResults = FZMoviesSearch::create()
    ->search('wrong')
    ->get(2);

//480mkv.com
$searchResults = FEMKVComSearch::create()
    ->search('love')
    ->get();
```
## [Examples](examples)

### No Licence :)