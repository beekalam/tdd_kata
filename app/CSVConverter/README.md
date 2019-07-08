# write a CSV to XML converter class
- Gets a string of CSV and convert it to XML
- XML schema is passed to the constructor with the following format

```php
[
  "child_element",
  [
    "name_of_field",
    "name_of_field",
    ...
  ]
]
```