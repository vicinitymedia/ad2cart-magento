# Vicinity Media - Ad2Cart for Magento 2

Enable customers to add items to their shopping cart on Magento, directly from a digital catalog. Customers can 
seamlessly add products to their cart while browsing the digital catalog, improving the user experience overall.

## Installation

### Composer

This package has not been added to packagist yet. You can install this package by adding the repository to your 
projects `composer.json` file:

```bash
...
    "repositories": {
      "ad2cart": {
        "type": "vcs",
        "url": "https://bitbucket.org/vicinity-media/ad2cart-magento.git",
        "only": [
          "vicinity-media/ad2cart"
        ]
      }
    }
...
```

Then run:

```bash
composer require vicinity-media/ad2cart
```

## Todo
- Unit tests
- Add module configuration doc
- Update installation doc
- Add module to package manager (TBD)
- Add pipeline to package registry on tagging

