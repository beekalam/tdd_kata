# write a `AutoCompleter` class that can make suggestions like what you see in your IDE.

```php
$ac = new AutoCompleter('some string');
$ac->suggest('so'); // should return an array of suggestions: ['some']
```

- suggestion function should be able to prioritize based on the frequency of words.