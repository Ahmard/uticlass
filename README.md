# Uticlass
Pieces of classes jam-packed to ease day-too-day life :)


# Installation

Make sure that you have composer installed
[Composer](http://getcomposer.org).

If you don't have Composer run the below command
```bash
curl -sS https://getcomposer.org/installer | php
```

Now, let's install Guzwrap:

```bash
composer require ahmard/uticlass
```

After installing, require Composer's autoloader in your code:

```php
require 'vendor/autoload.php';
```

# Usage
```php
use Uticlass\Video\FZMovies;
use Uticlass\Video\NetNaija;

//Extract download links

//Fzmovies
$fzUrl = 'https://fzmovies.net/movie-Moana%202016--hmp4.htm';
$dlLink1 = new (FZMovies($fzUrl))->get();

//Netnaija
$nnUrl = 'https://www.thenetnaija.com/videos/movies/6856-hot-summer-nights-2017';
$dlLink2 = new(NetNaija($nnUrl))->get()->linkTwo();
```

## [Examplez](examples)

### USIHOYUSEF :)