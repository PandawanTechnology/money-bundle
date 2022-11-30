# Features:
- PHP 8.0 attributes' support
- automatically add Form type
- automatically add DB types for mapping (doctrine only): `amount` and `currency` columns' mappings

# Configuration
```yaml
# config/packages/pandawan_technology_money.yaml
pandawan_technology_money:
    default_currency: EUR # Required, any ISO currency's code
    default_locale: "%kernel.default_locale%" # Optional, default to "%kernel.default_locale%"
```

# Usage
## Doctrine's mapping:
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

## Forms in twig
```yaml
# config/packages/twig.yaml
twig:
    form_themes:
        # …
        - '@PandawanTechnologyMoney/Form/bootstrap_4.html.twig'
```