mail-template-bundle
====================

Bundle add section in SonataAdmin-backend for management of mail templates. List of templates defined in configuration, but actual template code contained in the database (using Doctrine ORM). Each template has unique symbolic code. Template body can be used variable values, which list is defined by configuration.


# Configuration example

```
timurib_mail_template:
  templates:

    message_a:
      label: Lorem ipsum dolor sit amet
      vars: [ foo, bar ]

    message_b:
      label: Duis aute irure dolor in reprehenderit
      vars: [ baz ]
    …
```

# Usage

```php
// in controller
$this->get('timurib.mail_template.manager')->send('message_a', array(
    'foo' => 'hello',
    'bar' => 'world',
))
```