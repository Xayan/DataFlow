# DataFlow

###### A multi-purpose data manipulation library

A data manipulation library is meant to, well, manipulate data. This library introduces two main classes: `Entity` and `EntityCollection`. The first one accumulates data, the second one allows batch operations on entities. But that's not all, another feature of this library is data import/export. As for now, only CSV files are supported, but more are to come. That way you can easily make scripts that will import some data, process it and then output it in desired form (a string or a file).

## Installation

Package is available through composer:

`composer require xayan/dataflow`

## Usage

Let's assume the following CSV file:

`λ cat your_file.csv`
```csv
id,firstName,lastName,age
1,John,Doe,10
2,Mickey,Mouse,50
3,Philip J.,Fry,25
```

Now, let's say we want to filter this data to contain only adults. Also, I don't like the fact that first name and last name are separated. So let's change that:

```php
<?php
require_once 'vendor/autoload.php';

use DataFlow\Data\Entity;
use DataFlow\IO\CSV\Export;
use DataFlow\IO\CSV\ExportPolicy;
use DataFlow\IO\CSV\Import;
use DataFlow\IO\CSV\ImportPolicy;

$file = 'your_file.csv';

$header = fgetcsv(fopen($file, 'r'));

// This will create ImportPolicy that will import all available columns, using file header as a reference:
$importPolicy = ImportPolicy::fromHeader($header);

$collection = Import::fromFile($file, $importPolicy);

$newCollection = $collection->filter(function(Entity $entity) {
    // Select only entities that contain the 'age' property and its value is >= 18
    return $entity->has('age') && $entity->get('age') >= 18;
})->map(function(Entity $entity) {
    // Set a new property
    $entity->set('name', $entity->get('firstName') . ' ' . $entity->get('lastName'));

    // Remove properties
    $entity->remove('firstName');
    $entity->remove('lastName');

    // Remember to return it
    return $entity;
});

// Create ExportPolicy automatically from an entity, so all data will be exported
$exportPolicy = ExportPolicy::fromEntity($newCollection->get(0));

// Create a new file and write export contents to it
$outputFile = fopen('another_file.csv', 'w');
Export::toStream($outputFile, $newCollection, $exportPolicy);
```

If you execute this script, the output should look like this:

`λ cat another_file.csv`
```csv
id,age,name
2,50,"Mickey Mouse"
3,25,"Philip J. Fry"
```

## Features

Current version of the library is v1.0. Only the basic features are implemented, but they still have some potential.

#### What it can do now

- Each Entity can have any number of properties of any primitive type
- EntityCollection implements basic functions for data manipulation, that is: `each`, `map`, `count` and `filter`
- Import from and export to CSV - you can specify from which columns/properties data should be imported to/exported from, and whether to throw exceptions or not when data is missing 
- Full unit tests are implemented to make sure future changes won't mess up current functionality

#### Plans for the future

- JSON support
- PDO support
- Child entities
- Type definitions
- Data validation

## Footnotes

This library is a private project and I can't guarantee it will work 100% of the time. 