# Features:
- PHP 8.0 attributes' support
- automaticaly add Form type
- automaticaly add DB types for mapping (doctrine only): `amount` and `currency` columns' mappings

# Usage
## doctrine's mapping:
```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PandawanTechnology\Money\Model\Money;

#[ORM\Entity]
class Product {
    #[ORM\Embedded(class: Money::class)]
    private $price;
} 
```
