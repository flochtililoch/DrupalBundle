Drupal Bundle
====================

Introduction
---------------------

Provides Drupal API within your Symfony2 project.


Requirements
---------------------

* [Drupal 6.22](https://github.com/drupal/drupal/tree/6.22) "Drupal 6.22 on Github") (not tested with later versions)


Installation
---------------------

### Download FlochDrupalBundle and add Drupal 6.22 to your vendors

Files should be downloaded to 
`vendor/bundles/Floch/DrupalBundle` directory.

Here are two different method to achieve this:

**Using the vendors script**

Add the following lines in your `deps` file:

```
[FlochDrupalBundle]
    git=git://github.com/flochtililoch/DrupalBundle.git
    target=bundles/Floch/DrupalBundle

[Drupal]
    git=git://github.com/drupal/drupal.git
    target=Drupal/Drupal
```

Now, run the vendors script to download the bundle alongside with Drupal:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/flochtililoch/DrupalBundle.git vendor/bundles/Floch/DrupalBundle
$ git submodule add git://github.com/drupal/drupal.git vendor/Drupal/Drupal
$ git submodule update --init
$ cd vendor/Drupal/Drupal && git reset --hard 6.22
```

### Configure the Autoloader

Add the `Floch` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Floch' => __DIR__.'/../vendor/bundles',
));
```

### Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Floch\DrupalBundle\FlochDrupalBundle(),
    );
}
```

### Configure your Drupal installation

Add the following configuration to your parameters.ini file, replacing values with your settings:

``` yaml
drupal_path       = /your/drupal/installation/path
drupal_db_url     = mysqli://user@host/dbname
drupal_db_prefix  = tablesPrefix_
```