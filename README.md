# Soft delete children when parent soft deletes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dillingham/soft-deletes-parent.svg?style=flat-square)](https://packagist.org/packages/dillingham/soft-deletes-parent)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/dillingham/soft-deletes-parent/run-tests?label=tests)](https://github.com/dillingham/soft-deletes-parent/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/dillingham/soft-deletes-parent/Check%20&%20fix%20styling?label=code%20style)](https://github.com/dillingham/soft-deletes-parent/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dillingham/soft-deletes-parent.svg?style=flat-square)](https://packagist.org/packages/dillingham/soft-deletes-parent)

---

Automatically soft delete a model's children while maintaining their own soft deleted state when you restore the parent model.

---

## Installation

You can install the package via composer:

```bash
composer require dillingham/soft-deletes-parent
```

## Usage
Add the `parent_deleted_at` column to your table:
```php
Schema::table('posts', function (Blueprint $table) {
    $table->softDeletesParent();
});
```
And add the trait and parent model to your child model:
```php
<?php

namespace App\Models;

use App\Models\Author;
use Dillingham\SoftDeletesParent\SoftDeletesParent;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletesParent;

    protected static $softDeletesParent = Author::class;
}
```

Now Post's `parent_deleted_at` will update whenever an `App\Models\Author` is deleted or restored. This allows you to maintain the original `deleted_at` for the Post model after parent is restored. The post model will scope queries to exclude any where the parent iss deleted. 

### Scopes

With parent trashed:
```php
Post::withParentTrashed()->get();
```
Only parent trashed:
```php
Post::onlyParentTrashed()->get();
```

## Testing

```bash
composer test
```
## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Brian Dillingham](https://github.com/dillingham)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
